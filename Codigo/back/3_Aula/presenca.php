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
    
		$sql_verifica_presenca = "SELECT 
	  count(*) as quantaspresencas 
    FROM presenca a 
    where cpfCliente = '".str_replace(".","",str_replace("-","",$_POST['cliente']))."'
    and id_aula = ".$_POST["idaula"]."
    and diaSemana = '".$_POST["dataaula"]."' ";

    $result_qtpres  = mysqli_query($conn, $sql_verifica_presenca);
  
    while($rowpr = mysqli_fetch_assoc($result_qtpres)) 
    {//MONTA A LISTA PARA EXIBIR NA TELA
       $quantaspresencas  = $rowpr["quantaspresencas"];	
    }


    if($quantaspresencas > 0 )
    {

      $conn->close();		
      echo '<script>window.alert("Cliente com CPF '.$_POST['cliente'].' já está com presença na aula neste dia da semana .");</script>';
      echo '<script>history.back();</script>';

      exit;
    }



			
				try
				{
					$sql_insert = "insert into presenca 
									(nomeAula, cpfCliente , nomeInstrutor_0, diaSemana, id_aula) 
									values
                  (
                    '".mb_convert_case($_POST["nomeaula"], MB_CASE_LOWER, $encoding)."'
                    ,'".str_replace(".","",str_replace("-","",$_POST['cliente']))."'
                    ,'".mb_convert_case($_POST['instrutor'], MB_CASE_LOWER, $encoding)."'
                    ,'".$_POST["dataaula"]."' 
                    ,".$_POST["idaula"]."
                  )";

					if(mysqli_query($conn, $sql_insert))
					{
						$conn->close();		
						echo '<script>window.alert("Aluno com o CPF '.$_POST['cliente'].' presente na aula '.$_POST["nomeaula"].'  com sucesso");</script>';
						echo '<script>window.location.href="listaraula.php";</script>';
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
		a.id_aula
    ,a.cpfCliente
		,a.nomeAula
    ,a.nomeInstrutor_0
    ,a.diaSemana
    FROM presenca a ";


    if(trim($_REQUEST["id"]) != "") 
    { //CONSULTA A LISTA DE PRESENÇA PELA AULA
    $sql_lista .= "	where a.id_presenca = ".$_REQUEST["id"]." ";
    }		

		  $result_lista = mysqli_query($conn, $sql_lista);
		
		  while($row = mysqli_fetch_assoc($result_lista)) 
      {//MONTA A LISTA PARA EXIBIR NA TELA
      $idaula       = $row["id_aula"];
			$nomeaula 	    = $row["nomeAula"];
      $instrutor 	    = $row["nomeInstrutor_0"];		      	
      $horainicio	    = $row["diaSemana"];
      $cliente_sel     = $row["cpfCliente"];
      }	
      
      

      $sql_nome_aula = "SELECT nomeAula, nomeInstrutor_0 , horarioInicio, horarioFim , diasDaSemana from aula where id_aula =".$idaula;

      $result_nmaula  = mysqli_query($conn, $sql_nome_aula);
  
      while($row = mysqli_fetch_assoc($result_nmaula)) 
      {//MONTA A LISTA PARA EXIBIR NA TELA
         $nomeaula  = $row["nomeAula"];			        
         $instrutor  = $row["nomeInstrutor_0"];	
         $horarioInicio  = $row["horarioInicio"];	
         $horarioFim  = $row["horarioFim"];	                     
         $diasDaSemana  = $row["diasDaSemana"];	              
      }    
  
      $horainia = explode(" ", $horarioInicio);
      $horainiaula = $horainia[1];
      $horainif = explode(" ", $horarioFim);
      $horafimaula = $horainif[1];   
      
      
      $sql_cliente = "SELECT 
      cpfCliente
      ,nomeCliente
      FROM cliente 
      order by 2 ";

            
      $result_cliente  = mysqli_query($conn, $sql_cliente);

      while($row = mysqli_fetch_assoc($result_cliente)) 
      {//MONTA A LISTA PARA EXIBIR NA TELA
        $cpf_clientes[]  = $row["cpfCliente"];			     
        $clientes[]      = $row["nomeCliente"];			      
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

		$sql_verifica_presenca = "SELECT 
	  count(*) as quantaspresencas 
    FROM presenca a 
    where cpfCliente = '".str_replace(".","",str_replace("-","",$_POST['cliente']))."'
    and id_aula = ".$_POST["idaula"]."
    and diaSemana = '".$_POST["dataaula"]."' 
    and id_presenca not in (".$_REQUEST['id'].") ";

    $result_qtpres  = mysqli_query($conn, $sql_verifica_presenca);
  
    while($rowpr = mysqli_fetch_assoc($result_qtpres)) 
    {//MONTA A LISTA PARA EXIBIR NA TELA
       $quantaspresencas  = $rowpr["quantaspresencas"];	
    }


    if($quantaspresencas > 0 )
    {

      $conn->close();		
      echo '<script>window.alert("Cliente com CPF '.$_POST['cliente'].' já está com presença na aula neste dia da semana .");</script>';
      echo '<script>history.back();</script>';

      exit;
    }


				try
				{
					$sql_update = "update presenca 
                  set 
                    cpfCliente = '".str_replace(".","",str_replace("-","",$_POST['cliente']))."'
                  , nomeInstrutor_0 = '".mb_convert_case($_POST["instrutor"], MB_CASE_LOWER, $encoding)."'
                  , diaSemana = '".$_POST["dataaula"]."' 
									where id_presenca = ".$_REQUEST['id']." 
                  ";
                  

					if(mysqli_query($conn, $sql_update))
					{
						$conn->close();		
						echo '<script>window.alert("Presença da '.$_POST['aula'].' atualizada com sucesso");</script>';
						echo '<script>window.location.href="listarpresenca.php?idaula='.$_POST['idaula'].'";</script>';
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

				try
				{
					$sql_update = "delete from presenca 
									where id_presenca = ".$_REQUEST['id']." 
					";


					if(mysqli_query($conn, $sql_update))
					{
						$conn->close();		
						echo '<script>window.alert("Presença excluída com sucesso");</script>';
						echo '<script>window.location.href="listarpresenca.php?idaula='.$_REQUEST["idaula"].'";</script>';
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
else if($_REQUEST['acao'] == "cadastro")
{	


    $conn = mysqli_connect($servidor, $usuario, $senha, $banco);

    //////PREPARA A LISTA DE CLIENTES

    $cpf_clientes = array();
    $clientes = array();

    // Check connection
    if (!$conn) 
    {
          die("Falha na Conexão: " . mysqli_connect_error());
    }
    else
    {
        $sql_cliente = "SELECT 
        cpfCliente
        ,nomeCliente
        FROM cliente 
        order by 2 ";

              
        $result_cliente  = mysqli_query($conn, $sql_cliente);

        while($row = mysqli_fetch_assoc($result_cliente)) 
        {//MONTA A LISTA PARA EXIBIR NA TELA
          $cpf_clientes[]  = $row["cpfCliente"];			     
          $clientes[]      = $row["nomeCliente"];			      
        }


        $sql_nome_aula = "SELECT nomeAula, nomeInstrutor_0 , horarioInicio, horarioFim, diasDaSemana from aula where id_aula =".$_REQUEST["aula"];

        $result_nmaula  = mysqli_query($conn, $sql_nome_aula);

        while($row = mysqli_fetch_assoc($result_nmaula)) 
        {//MONTA A LISTA PARA EXIBIR NA TELA
          $nomeaula  = $row["nomeAula"];			        
          $instrutor  = $row["nomeInstrutor_0"];	
          $horarioInicio  = $row["horarioInicio"];	
          $horarioFim  = $row["horarioFim"];	                     
          $diasDaSemana  = $row["diasDaSemana"];	            
        }    

        $horainia = explode(" ", $horarioInicio);
        $horainiaula = $horainia[1];
        $horainif = explode(" ", $horarioFim);
        $horafimaula = $horainif[1];            
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
    <title>Academia de Gin&aacute;stica · Lista de Presença da aula <?php echo $nomeaula;?></title>

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
    <?php //VERIFICA SE A TELA FOI CHAMADA PARA CADASTRO OU EDIÇÃO. SE FOR PARA EDIÇÃO JÁ CARREGA OS CAMPOS E BLOQUEIA O CHAVE CPF ?>
  <p class="h1"><?php if($_REQUEST['acao'] == 'edicao') { ?> Editar <?php } else {   ?> Cadastrar <?php } ?> Presença da Aula <?php echo $nomeaula;?></p>
<?php 
if($_REQUEST['acao'] == 'edicao') 
{ 
?> 
  <form name="cadastro" action="presenca.php?acao=atualizar" method="post" enctype="multipart/form-data" onSubmit="return verificaCamposPres();">
<?php  
}
else
{
?>
  <form name="cadastro" action="presenca.php?acao=gravar" method="post" enctype="multipart/form-data" onSubmit="return verificaCamposPres();">	
<?php 
}
?>
    
  <div class="form-group">
    <label for="inputAula">Nome da Aula</label>
    <input type="text" class="form-control" id="aula" name="aula" maxlength="10"  value="<?php echo $nomeaula." - Das ".$horainiaula." a ".$horafimaula;?>" readonly  placeholder="Body Dance">
  </div>
  <div class="form-group">

 
 <div class="input-group mb-3">
 <div class="input-group-prepend">
     <label class="input-group-text" for="inputGroupSelect01">Dia da Aula</label>
 </div>
 <select class="custom-select" id="dataaula" name="dataaula">
   <option value="">Selecione</option>
<?php 
$dia = explode(" ", $diasDaSemana);

for($i=0;$i<count($dia);$i++)
{
  switch(trim($dia[$i]))
  {
    case "1" : $dia_da_semana = "Segunda"; break;
    case "2" : $dia_da_semana = "Terça"; break;
    case "3" : $dia_da_semana = "Quarta"; break;
    case "4" : $dia_da_semana = "Quinta"; break;
    case "5" : $dia_da_semana = "Sexta"; break;
    case "6" : $dia_da_semana = "Sábado"; break;               

  }
  if(trim($dia[$i])!= "")
  {
?>
   <option value="<?php echo $dia[$i];?>" <?php if(trim($dia[$i]) == trim($horainicio)) { ?> selected <?php } ?> >    
   <?php echo $dia_da_semana; ?>
   </option>
<?php
  }
}
?>
</select>
</div>  

  <div class="form-group">
  <label for="inputAula">Nome do Instrutor</label>
    <input type="text" class="form-control" id="instrutor" name="instrutor" maxlength="10"  value="<?php echo $instrutor;?>" readonly  placeholder="Body Dance">
  </div>  
    
    <div class="input-group mb-3">
      <div class="input-group-prepend">
          <label class="input-group-text" for="inputGroupSelect01">Cliente</label>
      </div>
      <select class="custom-select" id="cliente" name="cliente">
        <option value="">Selecione</option>
    <?php 
    for($i=0;$i<count($clientes);$i++)
    {
    ?>
        <option value="<?php echo $cpf_clientes[$i];?>" <?php if(trim($cpf_clientes[$i]) == trim($cliente_sel)) { ?> selected <?php } ?> >    
        <?php echo $clientes[$i]; ?>
        </option>
    <?php
    }
    ?>
    </select>
    </div>    
    <?php 
    if($_REQUEST['aula'] != "") 
    {
    ?> 
    <input type="hidden" name="idaula" value="<?php echo $_REQUEST["aula"];?>" >
    <input type="hidden" name="nomeaula" value="<?php echo $nomeaula;?>" >    
    <input class="btn btn-primary btn-lg" type="submit" value="Confirmar" >
    <a class="btn btn-secondary btn-lg" href="listarpresenca.php?idaula=<?php  echo $_REQUEST["aula"] ; ?>" role="button">Cancelar</a>    
    <?php
    }
    else
    {
    ?>
    <input type="hidden" name="id" value="<?php echo $_REQUEST["id"];?>" >
    <input type="hidden" name="idaula" value="<?php echo $idaula;?>" >    
    <input class="btn btn-primary btn-lg" type="submit" value="Confirmar" >
    <a class="btn btn-secondary btn-lg" href="listarpresenca.php?idaula=<?php  echo $idaula ; ?>" role="button">Cancelar</a>    
    <?php 
    }
    ?>
</form>
<div class="error-message" id="errosform" style=" display:none;"></div>	
    </div>
    </div>
</main><!-- /.container -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script></body>
</html>
