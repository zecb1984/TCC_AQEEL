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
	{	
		$sql_consulta = "SELECT count(*) as quantos FROM cliente 
				where cpfCliente = '".$_POST['cliente']."' 
				or lower(nomeCliente) like '%".$_POST['cliente']."%' ";
		
			
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
		where cpfCliente = '".$_POST['cliente']."' 
		or lower(nomeCliente) like '%".$_POST['cliente']."%' ";
					  
		  $result_lista = mysqli_query($conn, $sql_lista);
		
		  while($row = mysqli_fetch_assoc($result_lista)) 
		  {
			$cpfs[] 	 = $row["cpfCliente"];
			$nomes[] 	 = $row["nomeCliente"];			
			$rgs[] 		 = $row["identidadeCliente"];
			$enderecos[] = $row["enderecoCliente"];			
		  }		
				

		echo "<pre>";
		
		var_dump($cpfs); exit;

					  
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
  </head>
  <body>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">

  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="http://<?php echo $_SERVER["HTTP_HOST"];?>">Home<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="/back/1_Cliente/ConsultarCliente.html">Cliente</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/back/2_Instrutor/ConsultarInstrutor.html">Instrutor</a>
      </li>
      <li class="nav-item">
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

  <p class="h1">Listar Cliente</p>

  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">#</th>
        <th scope="col">First</th>
        <th scope="col">Last</th>
        <th scope="col">Handle</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">
          <input type="radio" name="radios" id="radio1" />
        </th>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
      </tr>
      <tr>
        <th scope="row">
          <input type="radio" name="radios" id="radio1" />
        </th>
        <td>Jacob</td>
        <td>Thornton</td>
        <td>@fat</td>
      </tr>
      <tr>
        <th scope="row">
          <input type="radio" name="radios" id="radio1" />
        </th>
        <td>Larry</td>
        <td>the Bird</td>
        <td>@twitter</td>
      </tr>
    </tbody>
  </table>
    
        <div class="btn-group" role="group" aria-label="Basic example">
    <a class="btn btn-info btn-lg" href="../1_Cliente/2_Pagamento/ListarPagamento.html" role="button">Pagamento</a>
    <a class="btn btn-info btn-lg" href="../1_Cliente/3_Ferias/ListarFerias.html" role="button">Férias</a>
    <a class="btn btn-info btn-lg" href="../1_Cliente/4_Avaliacao/ListarAvaliacao.html" role="button">Avaliação Física</a>
        </div><p>
        <div class="btn-group" role="group" aria-label="Basic example">
    <a class="btn btn-primary btn-lg" href="../1_Cliente/1_Cliente/CadastrarCliente.html" role="button">Cadastrar</a>
    <a class="btn btn-primary btn-lg" href="../1_Cliente/1_Cliente/EditarCliente.html" role="button">Editar</a>
    <a class="btn btn-primary btn-lg" href="../1_Cliente/1_Cliente/DeletarCliente.html" role="button">Deletar</a>
        </div>
    <a class="btn btn-secondary btn-lg" href="../1_Cliente/ConsultarCliente.html" role="button">Cancelar</a>

    </div>
    </div>
        
</main><!-- /.container -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script></body>
</html>
