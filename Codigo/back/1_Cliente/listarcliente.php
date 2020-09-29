<?php

	$cliente = $_POST['cliente'];

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
		$sql_consulta = "SELECT count(*) as quantos FROM cliente ";
		
		if(trim($_POST["cliente"]) != "") 
		{ //CONSULTA O CLIENTE PELO O NOME OU PELO CPF
		$sql_consulta .= "	where cpfCliente like '%".$_POST['cliente']."%' 
				or lower(nomeCliente) like '%".mb_strtolower($_POST["cliente"],'UTF-8')."%' ";
		}		
		
			
		$result = mysqli_query($conn, $sql_consulta);
			
		while($row = mysqli_fetch_assoc($result)) 
		{
		$quantos = $row["quantos"];
		}		


		$cpfs 		= array();
		$nomes 		= array();
		$rgs        = array();
		$enderecos  = array();
		
		$sql_lista = "SELECT 
		 cpfCliente 
		,nomeCliente
		,identidadeCliente
		,enderecoCliente
		FROM cliente 
		where cpfCliente like '%".$_POST['cliente']."%' 
		or lower(nomeCliente) like '%".$_POST['cliente']."%' ";
					  
		  $result_lista = mysqli_query($conn, $sql_lista);
		
		  while($row = mysqli_fetch_assoc($result_lista)) 
		  {//MONTA A LISTA PARA EXIBIR NA TELA
			$cpfs[] 	 = $row["cpfCliente"];
			$nomes[] 	 = $row["nomeCliente"];			
			$rgs[] 		 = $row["identidadeCliente"];
			$enderecos[] = $row["enderecoCliente"];			
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
    <title>Academia de Ginastica · Cliente</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/starter-template/">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

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
	<script src="../../../js/validacoes.js?1254"></script>
  </head>
  <body>
 


<main role="main" class="container">
    
<div class="jumbotron">
    <div class="col-sm-8 mx-auto">

  <p class="h1">Listar Cliente</p>

  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nome</th>
        <th scope="col">CPF</th>
        <th scope="col">RG</th>
        <th scope="col">Endere&ccedil;o</th>		
      </tr>
    </thead>
<?php 
if($quantos > 0)	
{
	for($i=0;$i<count($cpfs);$i++)
	{	
?>	
    <tbody>
      <tr>
        <th scope="row">
          <input type="radio" name="selecao" value="<?php echo $cpfs[$i];?>" id="selecao" onClick="preencheBotoesConsultaCliente(this.value);" />
        </th>
        <td><?php echo $nomes[$i];?></td>
        <td><?php echo $cpfs[$i];?></td>
        <td><?php echo $rgs[$i];?></td>
        <td><?php echo $enderecos[$i];?></td>		
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
  </table>
    
        <div class="btn-group" role="group" aria-label="Basic example">
        </div><p>
        <div class="btn-group" role="group" aria-label="Basic example">
    <a class="btn btn-primary btn-lg" href="../1_Cliente/1_Cliente/cliente.php?acao=cadastro" role="button">Cadastrar</a>
<?php 
if($quantos > 0) 
{
?>
	<div id="botoesacao"></div>  
<?php
}
?>	
        </div>
    <a class="btn btn-secondary btn-lg" href="../1_Cliente/ConsultarCliente.html" role="button">Cancelar</a>

    </div>
    </div>
        
</main><!-- /.container -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script></body>
</html>
