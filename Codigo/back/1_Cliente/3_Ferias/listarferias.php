<?php

	$cliente = $_POST['cliente'];

	require_once("../../../conexao.php");

	// Create connection
	$conn = mysqli_connect($servidor, $usuario, $senha, $banco);

	// Check connection
	if (!$conn) 
	{
			  die("Falha na Conexão: " . mysqli_connect_error());
	}
	else
	{	//VERIFICA SE FOI DIGITADO UM PARÂMETRO DE PESQUISA. SE NÃO, LISTA TODOS OS CLIENTES
		$sql_consulta = "SELECT count(*) as quantos FROM ferias ";
		
		if(trim($_REQUEST["cpf"]) != "") 
		{ //CONSULTA O CLIENTE PELO O NOME OU PELO CPF
		$sql_consulta .= "	where cpfCliente like '%".$_REQUEST['cpf']."%' 
			 ";
		}		
		
			
		$result = mysqli_query($conn, $sql_consulta);
			
		while($row = mysqli_fetch_assoc($result)) 
		{
		$quantos = $row["quantos"];
		}		


		$datas 		= array();
		$dias 		= array();

		$sql_lista = "SELECT 
		 dataFerias
		,duracaoFerias
		FROM ferias 
		where cpfCliente like '%".$_REQUEST['cpf']."%'  ";
    
  
		  $result_lista = mysqli_query($conn, $sql_lista);
		
		  while($row = mysqli_fetch_assoc($result_lista)) 
		  {//MONTA A LISTA PARA EXIBIR NA TELA
			$datas[] 		 = $row["dataFerias"];
			$dias[] = $row["duracaoFerias"];			
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
    <title>Academia de Ginastica · Férias</title>

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
  <p class="h1">Listar Férias do CPF <?php echo $_REQUEST['cpf'];?></p>

  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">Selecionar</th>
        <th scope="col">Data</th>
        <th scope="col">Duração</th>
      </tr>
    </thead>
    <tbody>
    <?php 
if($quantos > 0)	
{
	for($i=0;$i<count($datas);$i++)
	{	
?>	
    <tbody>
      <tr>
        <th scope="row">
          <input type="radio" name="selecao" value="<?php echo $datas[$i];?>" id="selecao" onClick="preencheBotoesConsultaFerias('<?php echo $_REQUEST["cpf"];?>', '<?php echo $datas[$i];?>');" />
        </th>
        <td><?php $dataori = explode("-",$datas[$i]); echo $dataori[2]."/".$dataori[1]."/".$dataori[0];?></td>
        <td><?php echo $dias[$i];?></td>
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
  
  <a class="btn btn-primary btn-lg" href="ferias.php?cadastro&cpf=<?php echo $_REQUEST["cpf"];?>" role="button">Cadastrar</a>
  <a class="btn btn-secondary btn-lg" href="../listarcliente.php?cpf=<?php echo $_REQUEST["cpf"];?>" role="button">Cancelar</a>
  <?php 
if($quantos > 0) 
{
?>
	<div id="botoesacao"></div>  
<?php
}
?>	  
    </div>
    </div>
</main><!-- /.container -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script></body>
</html>
