<?php
require_once("../../conexao.php");
$encoding = 'UTF-8';
if($_REQUEST['acao'] == "gravar")
{

	// Create connection
	$conn = mysqli_connect($servidor, $usuario, $senha, $banco);

	// Check connection
	if (!$conn) 
	{
			  die("Falha na Conexão: " . mysqli_connect_error());
	}
	else
	{
				
					$sql = "SELECT count(*) as quantos FROM instrutor where cpfInstrutor = '".str_replace(".","",str_replace("-","",$_POST['cpf']))."' ";
					
			
					$result = mysqli_query($conn, $sql);
			
					  while($row = mysqli_fetch_assoc($result)) 
					  {
						$quantos = $row["quantos"];
					  }
			//VERIFICA PRIMEIRO SE O INSTRUTOR JÁ ESTÁ CADASTRADO COM O CPF INCLUÍDO NO FORMULÁRIO
			
			if($quantos >0)
			{		
				$conn->close();		
				echo '<script>window.alert("Instrutor com CPF '.$_POST['cpf'].' já está cadastrado.");</script>';
				echo '<script>history.back();</script>';

				exit;
				
			}
			else
			{
				
					$sql = "SELECT count(*) as quantosins FROM instrutor where lower(nomeInstrutor) = '".mb_convert_case(str_replace(" ","",$_POST['nome']), MB_CASE_LOWER, $encoding)."' ";
					
			
					$result = mysqli_query($conn, $sql);
			
					  while($row = mysqli_fetch_assoc($result)) 
					  {
						$quantosins = $row["quantosins"];
					  }		
				if($quantosins >0)
				{		
					$conn->close();		
					echo '<script>window.alert("Já existe um Instrutor com este nome  '.$_POST['nome'].' .");</script>';
					echo '<script>history.back();</script>';

					exit;
					
				}					  

							
				
				
				try
				{
					$sql_insert = "insert into instrutor 
									(cpfInstrutor , nomeInstrutor, identidadeInstrutor, tipoAtividade) 
									values
									('".str_replace(".","",str_replace("-","",$_POST['cpf']))."','".mb_convert_case($_POST['nome'], MB_CASE_LOWER, $encoding)."','".str_replace(".","",str_replace("-","",$_POST['rg']))."','".mb_convert_case($_POST['atividade'], MB_CASE_LOWER, $encoding)."')	";

					if(mysqli_query($conn, $sql_insert))
					{
						$conn->close();		
						echo '<script>window.alert("Instrutor com CPF '.$_POST['cpf'].' cadastrado com sucesso");</script>';
						echo '<script>window.location.href="ConsultarInstrutor.html";</script>';
						exit;	
					}
					else
                    {
                        throw new exception (mysqli_error($conn));
                    }
				
				}
				catch(Exception $e)
				{
					$conn->close();	
					echo '<script>window.alert("'.$e->getMessage().'");</script>';
					echo '<script>history.back();</script>';
					exit;			
				}					

			}	
	}
	
}
else if($_REQUEST['acao'] == "edicao")
{
	
	// Create connection
	$conn = mysqli_connect($servidor, $usuario, $senha, $banco);

	// Check connection
	if (!$conn) 
	{
			  die("Falha na Conexão: " . mysqli_connect_error());
	}
	else
	{	

		
		$sql_lista = "SELECT 
		 cpfInstrutor
		,nomeInstrutor
		,identidadeInstrutor
		,tipoAtividade
		FROM instrutor 
		where cpfInstrutor = '".$_REQUEST['cpf']."'  ";
					  
		  $result_lista = mysqli_query($conn, $sql_lista);
		//MONTA NA TELA O CLIENTE PARA EDIÇÃO
		  while($row = mysqli_fetch_assoc($result_lista)) 
		  {
			$cpf 	   = $row["cpfInstrutor"];
			$nome 	   = $row["nomeInstrutor"];			
			$rg		   = $row["identidadeInstrutor"];
			$atividade = $row["tipoAtividade"];			
		  }		
				
					  
	}
	
}
else if($_REQUEST['acao'] == "atualizar")
{
	// Create connection
	$conn = mysqli_connect($servidor, $usuario, $senha, $banco);

	// Check connection
	if (!$conn) 
	{
			  die("Falha na Conexão: " . mysqli_connect_error());
	}
	else
	{	
				try
				{
					$sql_update = "update instrutor 
									set 
									nomeInstrutor = '".mb_convert_case($_POST['nome'], MB_CASE_LOWER, $encoding)."' ,
									identidadeInstrutor =  '".str_replace(".","",str_replace("-","",$_POST['rg']))."' ,
									tipoAtividade = '".mb_convert_case($_POST['atividade'], MB_CASE_LOWER, $encoding)."'
									where cpfInstrutor = '".str_replace(".","",str_replace("-","",$_POST['cpf']))."' ";

					if(mysqli_query($conn, $sql_update))
					{
						$conn->close();		
						echo '<script>window.alert("Instrutor com CPF '.$_POST['cpf'].' atualizado com sucesso");</script>';
						echo '<script>window.location.href="ConsultarInstrutor.html";</script>';
						exit;	
					}
					else
                    {
                        throw new exception (mysqli_error($conn));
                    }
				
				}
				catch(Exception $e)
				{
					$conn->close();	
					echo '<script>window.alert("'.$e->getMessage().'");</script>';
					echo '<script>history.back();</script>';
					exit;			
				}	
	}				
}	
else if($_REQUEST['acao'] == "exclusao")
{
	// Create connection
	$conn = mysqli_connect($servidor, $usuario, $senha, $banco);

	// Check connection
	if (!$conn) 
	{
			  die("Falha na Conexão: " . mysqli_connect_error());
	}
	else
	{	


		$sql = "		
		select count(*) as quantosdadosinstrutor
		from instrutor a
        left join aula b on b.cpfInstrutor = a.cpfInstrutor
        left join presenca c on c.nomeInstrutor_0 = a.nomeInstrutor
		where 
		b.cpfInstrutor = '".str_replace(".","",str_replace("-","",$_REQUEST['cpf']))."'
        or c.nomeInstrutor_0 = (select d.nomeInstrutor from instrutor d where d.cpfInstrutor = '".str_replace(".","",str_replace("-","",$_REQUEST['cpf']))."')
		";
				
			
		$result = mysqli_query($conn, $sql);

		  while($row = mysqli_fetch_assoc($result)) 
		  {
			$quantos = $row["quantosdadosinstrutor"];
		  }
			//VERIFICA PRIMEIRO SE O CLIENTE POSSUI DADOS EM OUTRAS TABELAS

			if($quantos >0)
			{		
				$conn->close();		
				echo '<script>window.alert("instrutor com CPF '.$_REQUEST['cpf'].' possui Aula ou presença nas aulas cadastradas\n Exclua esses dados primeiro.");</script>';
				echo '<script>history.back();</script>';

				exit;
				
			}	



				try
				{
					$sql_update = "delete from instrutor where cpfInstrutor = '".str_replace(".","",str_replace("-","",$_REQUEST['cpf']))."' ";

					if(mysqli_query($conn, $sql_update))
					{
						$conn->close();		
						echo '<script>window.alert("Instrutor com CPF '.$_REQUEST['cpf'].' excluído com sucesso");</script>';
						echo '<script>window.location.href="ConsultarInstrutor.html";</script>';
						exit;	
					}
					else
                    {
                        throw new exception (mysqli_error($conn));
                    }
				
				}
				catch(Exception $e)
				{
					$conn->close();	
					echo '<script>window.alert("'.$e->getMessage().'");</script>';
					echo '<script>history.back();</script>';
					exit;			
				}	
	}				
}	
	

?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="ISO-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>Academia de Ginastica · Instrutor</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/starter-template/">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<script src="../../js/validacoes.js?1254"></script>
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
	  
	  .error-message {
	  background-color: #fce4e4;
	  border: 1px solid #fcc2c3;
	  padding: 20px 30px;
	}
	
	.error-text {
	  color: #cc0033;
	  font-family: Helvetica, Arial, sans-serif;
	  font-size: 13px;
	  font-weight: bold;
	  line-height: 20px;
	  text-shadow: 1px 1px rgba(250,250,250,.3);
	}	  
    </style>
    <!-- Custom styles for this template -->
    <link href="../starter-template.css" rel="stylesheet">
  </head>
  <body>


<main role="main" class="container">
    
    
<div class="jumbotron">
    <div class="col-sm-8 mx-auto">

    
<?php //VERIFICA SE A TELA FOI CHAMADA PARA CADASTRO OU EDIÇÃO. SE FOR PARA EDIÇÃO JÁ CARREGA OS CAMPOS E BLOQUEIA O CHAVE CPF ?>
  <p class="h1"><?php if($_REQUEST['acao'] == 'edicao') { ?> Editar <?php } else {   ?> Cadastrar <?php } ?>Instrutor</p>
<?php 
if($_REQUEST['acao'] == 'edicao') 
{ 
?> 
  <form name="cadastro" action="instrutor.php?acao=atualizar" method="post" enctype="multipart/form-data" onSubmit="return verificaCamposInstrutor();">
<?php  
}
else
{
?>
  <form name="cadastro" action="instrutor.php?acao=gravar" method="post" enctype="multipart/form-data" onSubmit="return verificaCamposInstrutor();">	
<?php 
}
?>

  <div class="form-group">
    <label for="inputNome">Nome</label>
    <input type="text" class="form-control" id="nome" name="nome" maxlength="30" <?php if ($nome != "") { ?>  value="<?php echo $nome;?>" <?php } ?>  placeholder="Fulano">
  </div>
  <div class="form-group">
    <label for="inputIdentidade">Identidade</label>
    <input type="text" class="form-control" id="rg" name="rg" maxlength="12" onKeyPress="MascaraRG(this);" <?php if ($rg != "") { ?>  value="<?php echo $rg;?>" <?php } ?> placeholder="12.345.678-9">
  </div>
  <div class="form-group">
    <label for="inputCPF">CPF</label>
    <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14" <?php if ($cpf != "") { ?>  value="<?php echo $cpf;?>" readonly <?php } ?> placeholder="123.456.789-01" onBlur="ValidarCPF(this);" onKeyPress="MascaraCPF(this);">
  </div>
  <div class="form-group">
    <label for="inputAtividade">Tipo de Atividade</label>
    <input type="text" class="form-control" id="atividade" name="atividade" maxlength="20" <?php if ($atividade != "") { ?>  value="<?php echo $atividade;?>" <?php } ?> placeholder="Body Combat">
  </div>
   <input class="btn btn-primary btn-lg" type="submit" value="Confirmar" >  
  <a class="btn btn-secondary btn-lg" href="ConsultarInstrutor.html" role="button">Cancelar</a>  
        </form>
	  <div class="error-message" id="errosform" style=" display:none;"></div>			




    </div>
    </div>
        
</main><!-- /.container -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script></body>
</html>
