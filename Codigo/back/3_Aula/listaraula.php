<?php

	$aula = $_POST['aula'];

  require_once("../../conexao.php");
  

	// Create connection
	$conn = mysqli_connect($servidor, $usuario, $senha, $banco);

	// Check connection
	if (!$conn) 
	{
			  die("Falha na Conexão: " . mysqli_connect_error());
	}
	else
	{	//VERIFICA SE FOI DIGITADO UM PARÂMETRO DE PESQUISA. SE NÃO, LISTA TODOS OS CLIENTES
		$sql_consulta = "SELECT count(*) as quantos FROM aula ";
		
		if(trim($_POST["aula"]) != "") 
		{ //CONSULTA O CLIENTE PELO O NOME OU PELO CPF
		$sql_consulta .= "	where lower(nomeAula) like '%".mb_strtolower($_POST["aula"],'UTF-8')."%' ";
		}		
		
			
		$result = mysqli_query($conn, $sql_consulta);
			
		while($row = mysqli_fetch_assoc($result)) 
		{
		$quantos = $row["quantos"];
		}		

    $ids = array();
		$aulas 		   = array();
		$instrutores   = array();
    $periodosi      = array();
		$periodosf      = array();        
		$dias          = array();
		
		$sql_lista = "SELECT 
		id_aula
    , cpfInstrutor 
		,nomeAula
    ,nomeInstrutor_0
		,horarioInicio
		,horarioFim
    ,diasDaSemana
		FROM aula ";


            if(trim($_POST["aula"]) != "") 
            { //CONSULTA A AULA PELO NOME 
            $sql_lista .= "	where lower(nomeAula) like '%".mb_strtolower($_POST["aula"],'UTF-8')."%' ";
            }	
                          
     
		  $result_lista = mysqli_query($conn, $sql_lista);
		
		  while($row = mysqli_fetch_assoc($result_lista)) 
      {//MONTA A LISTA PARA EXIBIR NA TELA
      $ids[]       = $row["id_aula"];
			$aulas[] 	    = $row["nomeAula"];
			$instrutores[] 	= $row["nomeInstrutor_0"];			
      $periodosi[] 	= $row["horarioInicio"];
      $periodosf[]    = $row["horarioFim"];            
			$dias[]         = $row["diasDaSemana"];			
		  }		
				
					  
	}


?>



<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>Academia de Ginastica · Aula</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/starter-template/">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="../../../js/validacoes.js?1254"></script>
    <!-- Bootstrap core CSS -->
    <link href="../assets/dist/css/bootstrap.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="../starter-template.css" rel="stylesheet">
  </head>
  <body>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">

  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="http://<?php echo $_SERVER["HTTP_HOST"];?>">Home<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/back/1_Cliente/ConsultarCliente.html">Cliente</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/back/2_Instrutor/ConsultarInstrutor.html">Instrutor</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="/back/3_Aula/ConsultarAula.html">Aula</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/back/4_Relatorios/ListarRelatorios.html">Relatórios</a>
      </li>
    </ul>
  </div>
</nav>

<main role="main" class="container">
<div class="jumbotron">
    <div class="col-sm-8 mx-auto">
  <p class="h1">Listar Aula</p>

  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nome</th>
        <th scope="col">Instrutor</th>
        <th scope="col">Período</th>
        <th scope="col">Dias da Semana</th>        
      </tr>
    </thead>
    <tbody>
    <?php 
if($quantos > 0)	
{
	for($i=0;$i<count($aulas);$i++)
	{	//MONTA O GRID CONFORME OS DIAS DESTA AULA
            $perinis = explode(" ",$periodosi[$i]);
            $periodo_sel_ini = substr($perinis[1],0,5);
            $perfims = explode(" ",$periodosf[$i]);
            $periodo_sel_fim = substr($perfims[1],0,5);   
            
            $diassel = explode(" ",$dias[$i]);
            $diasaula = "";
            for($j=0;$j<20;$j++)
            {

                
                if(trim($diassel[$j]) == "1")
                {
                    $diasaula = " Segunda <br>";
                }
                if(trim($diassel[$j]) == "2")
                {
                    $diasaula .= " Ter&ccedil;a <br>";
                }
                if(trim($diassel[$j]) == "3")
                {
                    $diasaula .= " Quarta <br>";
                }
                if(trim($diassel[$j]) == "4")
                {
                    $diasaula .= " Quinta <br>";
                }
                if(trim($diassel[$j]) == "5")
                {
                    $diasaula .= " Sexta <br>";
                }
                if(trim($diassel[$j]) == "6")
                {
                    $diasaula .= " S&aacute;bado <br>";
                } 
            }
?>		
    <tbody>
      <tr>
        <th scope="row">
          <input type="radio" name="selecao" value="<?php echo $ids[$i];?>" id="selecao" onClick="preencheBotoesConsultaAula(this.value);" />
        </th>
        <td><?php echo $aulas[$i];?></td>
        <td><?php echo $instrutores[$i];?></td>
        <td><?php echo $periodo_sel_ini." a ".$periodo_sel_fim;?></td>
        <td><?php echo $diasaula;?></td>		
      </tr>
    </tbody>
<?php
	}
}
else
{
?>	
    <tbody>
      <tr>
        <td colspan=5>Nenhum registro encontrado</td>
      </tr>
    </tbody>
<?php
}
?>	
    </tbody>
  </table>
  
  <a class="btn btn-primary btn-lg" href="aula.php?cadastro" role="button">Cadastrar</a>
  <?php 
if($quantos > 0) 
{
?>
	<div id="botoesacao"></div>  
<?php
}
?>	  
  <a class="btn btn-secondary btn-lg" href="ConsultarAula.html" role="button">Cancelar</a>
    </div>
    </div>
</main><!-- /.container -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script></body>
</html>
