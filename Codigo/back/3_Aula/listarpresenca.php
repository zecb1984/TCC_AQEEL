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
		$sql_consulta = "SELECT count(*) as quantos FROM presenca ";
		
		if(trim($_REQUEST["idaula"]) != "") 
		{ //CONSULTA O CLIENTE PELO O NOME OU PELO CPF
		$sql_consulta .= "	where id_aula = ".$_REQUEST["idaula"]." ";
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
		$alunos      = array();        
    $dias          = array();
    $nmalunos     = array();
		
		$sql_lista = "SELECT 
		a.id_presenca
    ,a.cpfCliente
    ,b.nomeCliente
		,a.nomeAula
    ,a.nomeInstrutor_0
    ,a.diaSemana
    FROM presenca a
    inner join cliente b on a.cpfCliente = b.cpfCliente ";


    if(trim($_REQUEST["idaula"]) != "") 
    { //CONSULTA A LISTA DE PRESENÇA PELA AULA
    $sql_lista .= "	where a.id_aula = ".$_REQUEST["idaula"]." ";
    }		
    

     
		  $result_lista = mysqli_query($conn, $sql_lista);
		
		  while($row = mysqli_fetch_assoc($result_lista)) 
      {//MONTA A LISTA PARA EXIBIR NA TELA
      $ids[]       = $row["id_presenca"];
			$aulas[] 	    = $row["nomeAula"];
      $instrutores[] 	= $row["nomeInstrutor_0"];		      	
      $periodosi[] 	= $row["diaSemana"];
      $alunos[]     = $row["cpfCliente"];
      $nmalunos[]   = $row["nomeCliente"];
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
        <a class="nav-link" href="JavaScript:window.close();">Fechar<span class="sr-only" >(current)</span></a>
      </li>
    </ul>
  </div>
</nav>

<main role="main" class="container">
<div class="jumbotron">
    <div class="col-sm-8 mx-auto">
  <p class="h1">Listar Presença na Aula <?php echo $aulas[$i];?></p>

  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nome Aula</th>
        <th scope="col">Instrutor</th>
        <th scope="col">Aluno Presente</th>
        <th scope="col">Dia da Semana</th>        
      </tr>
    </thead>
    <tbody>
    <?php 
if($quantos > 0)	
{
	for($i=0;$i<count($aulas);$i++)
	{	//MONTA O GRID CONFORME OS DIAS DESTA AULA
         

?>		
    <tbody>
      <tr>
        <th scope="row">
          <input type="radio" name="selecao" value="<?php echo $ids[$i];?>" id="selecao" onClick="preencheBotoesConsultaPres(this.value,<?php echo $_REQUEST["idaula"]?>);" />
        </th>
        <td><?php echo $aulas[$i];?></td>
        <td><?php echo $instrutores[$i];?></td>
        <td><?php echo $nmalunos[$i]." - CPF - ".$alunos[$i];?></td>
        <?php
        switch(trim($periodosi[$i]))
        {
          case "1" : $dia_da_semana = "Segunda"; break;
          case "2" : $dia_da_semana = "Terça"; break;
          case "3" : $dia_da_semana = "Quarta"; break;
          case "4" : $dia_da_semana = "Quinta"; break;
          case "5" : $dia_da_semana = "Sexta"; break;
          case "6" : $dia_da_semana = "Sábado"; break;   
        }

        ?>
        <td><?php echo $dia_da_semana;?></td>		
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
  
  <a class="btn btn-primary btn-lg" href="presenca.php?acao=cadastro&aula=<?php echo $_REQUEST['idaula'];?>" role="button">Cadastrar</a>
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
