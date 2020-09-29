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

    $sql = "SELECT count(*) as quantosaula FROM aula where lower(nomeAula) = '".mb_convert_case(str_replace(" ","",$_POST['aula']), MB_CASE_LOWER, $encoding)."' ";
					
			
    $result = mysqli_query($conn, $sql);

      while($row = mysqli_fetch_assoc($result)) 
      {
      $quantosaula = $row["quantosaula"];
      }		
  if($quantosaula >0)
  {		
    $conn->close();		
    echo '<script>window.alert("Já existe uma Aula com este nome  '.$_POST['aula'].' .");</script>';
    echo '<script>history.back();</script>';

    exit;
    
  }	
         //VERIFICA QUANTOS DIAS DA SEMANA FORAM SELECIONADOS, PARA MONTAR A SEQUÊNCIA A SER GRAVADA NO CAMPO diasDaSemana
    if(count($_POST["diasemana"]) == 1)
    {
      if($_POST["diasemana"][0] == "seg" )
      {
          $diadasemana = " 1 ";
      }
      if($_POST["diasemana"][0] == "ter" )
      {
          $diadasemana = " 2 ";
      }
      if($_POST["diasemana"][0] == "qua" )
      {
          $diadasemana = " 3 ";
      }
      if($_POST["diasemana"][0] == "qui" )
      {
          $diadasemana = " 4 ";
      }
      if($_POST["diasemana"][0] == "sex" )
      {
          $diadasemana = " 5 ";
      }
      if($_POST["diasemana"][0] == "sab" )
      {
          $diadasemana = " 6 ";
      }     
    }
    else
    { 
          for($i=0;$i<count($_POST["diasemana"]);$i++)
          {
              if($_POST["diasemana"][$i] == "seg" )
              {
                  $diadasemana .= " 1 ";
              }
              if($_POST["diasemana"][$i] == "ter" )
              {
                  $diadasemana .= " 2 ";
              }
              if($_POST["diasemana"][$i] == "qua" )
              {
                  $diadasemana .= " 3 ";
              }
              if($_POST["diasemana"][$i] == "qui" )
              {
                  $diadasemana .= " 4 ";
              }
              if($_POST["diasemana"][$i] == "sex" )
              {
                  $diadasemana .= " 5 ";
              }
              if($_POST["diasemana"][$i] == "sab" )
              {
                  $diadasemana .= " 6 ";
              }                                                                                                      
          }
    }

        $sql_instrutor = "SELECT 
        nomeInstrutor
        FROM instrutor 
        where cpfInstrutor like '%".$_POST['instrutor']."%' ";
    
              
        $result_instrutor  = mysqli_query($conn, $sql_instrutor);
    
        while($row = mysqli_fetch_assoc($result_instrutor)) 
        {//MONTA A LISTA PARA EXIBIR NA TELA
           $nomeinstrutor    = $row["nomeInstrutor"];			     
        }

        $diasparaconsulta = explode(" ",$diadasemana);

        if(count($diasparaconsulta) > 1)
        {
            for($d=0;$d<count($diasparaconsulta);$d++)
            {
              if(trim($diasparaconsulta[$d]) != "")
              {
                $diasselformatado .= " replace(trim(diasdaSemana),\" \",\"\") like '%".trim($diasparaconsulta[$d])."%' or";
              }
            }
        }
        else
        {
          $diasselformatado = "replace(trim(diasdaSemana),\" \",\"\") like '%".trim($diadasemana)."%' ";
        }
        

        $sql_checa_encavalamento = "
            select count(*) as quantasaulas from aula
            where cpfInstrutor = '".$_POST['instrutor']."' 
            and  ".$diasselformatado." and horarioInicio = STR_TO_DATE('01/01/2000 ".substr($_POST['horainicio'],0,2)."', '%d/%m/%Y %H')  ";

  
         //VERIFICA SE O INSTRUTOR JÁ TEM AULA MARCADA NO INTERVALO DE HORÁRIO SELECIONADO NOS DIAS DA SEMANA SELECIONADOS              
        $result_encavalamento  = mysqli_query($conn, str_replace("or and","and",$sql_checa_encavalamento));
    
        while($rowe = mysqli_fetch_assoc($result_encavalamento)) 
        {//MONTA A LISTA PARA EXIBIR NA TELA
           $quantasaulas    = $rowe["quantasaulas"];			     
        }

        if($quantasaulas >0)
        {		
          $conn->close();		
          echo '<script>window.alert("Já existe Aula com este instrutor no intervalo de horário selecionado em um dos dias da semana selecionados .");</script>';
          echo '<script>history.back();</script>';
      
          exit;
          
        }	
   
			
				try
				{
					$sql_insert = "insert into aula 
									(nomeAula, cpfInstrutor , nomeInstrutor_0, horarioInicio, horarioFim, diasDaSemana) 
									values
                  (
                    '".mb_convert_case($_POST["aula"], MB_CASE_LOWER, $encoding)."'
                    ,'".str_replace(".","",str_replace("-","",$_POST['instrutor']))."'
                    ,'".mb_convert_case($nomeinstrutor, MB_CASE_LOWER, $encoding)."'
                    ,STR_TO_DATE('01/01/2000 ".$_POST["horainicio"]."', '%d/%m/%Y %H:%i') 
                    ,STR_TO_DATE('01/01/2000 ".$_POST["horafim"]."', '%d/%m/%Y %H:%i') 
                    ,'".$diadasemana."'
                  )";

					if(mysqli_query($conn, $sql_insert))
					{
						$conn->close();		
						echo '<script>window.alert("Aula com o nome '.$_POST['aula'].' cadastrada com sucesso");</script>';
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
		id_aula
    , cpfInstrutor 
		,nomeAula
    ,nomeInstrutor_0
		,horarioInicio
		,horarioFim
    ,diasDaSemana
		FROM aula where id_aula = ".$_REQUEST['id']."    ";
		
					  
      $result_lista = mysqli_query($conn, $sql_lista);
      

		  
	
		//MONTA NA TELA A AULA PARA EDIÇÃO
		  while($row = mysqli_fetch_assoc($result_lista)) 
		  {
			$aula 	  		 = $row["nomeAula"];
			$instrutor_sel 	   = $row["cpfInstrutor"];	
      $horainicios    = $row["horarioInicio"];	
      $horafims     	 = $row["horarioFim"];	  
      $diassemana    = $row["diasDaSemana"];	  
	    }		
      $hrini = explode(" ", $horainicios)	;
      $horainicio = substr($hrini[1],0,5);
      $hrfim = explode(" ", $horafims)	;
      $horafim = substr($hrfim[1],0,5); 
      
      $diassel = explode(" ",$diassemana);

      for($j=0;$j<20;$j++)
      {

          
          if(trim($diassel[$j]) == "1")
          {
            $segunda = "s";
          }
          if(trim($diassel[$j]) == "2")
          {
            $terca = "s";
          }
          if(trim($diassel[$j]) == "3")
          {
            $quarta = "s";
          }
          if(trim($diassel[$j]) == "4")
          {
            $quinta = "s";
          }
          if(trim($diassel[$j]) == "5")
          {
            $sexta = "s";
          }
          if(trim($diassel[$j]) == "6")
          {
            $sabado = "s";
          } 
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

        //VERIFICA QUANTOS DIAS DA SEMANA FORAM SELECIONADOS, PARA MONTAR A SEQUÊNCIA A SER GRAVADA NO CAMPO diasDaSemana
        if(count($_POST["diasemana"]) == 1)
        {
          if($_POST["diasemana"][0] == "seg" )
          {
              $diadasemana = " 1 ";
          }
          if($_POST["diasemana"][0] == "ter" )
          {
              $diadasemana = " 2 ";
          }
          if($_POST["diasemana"][0] == "qua" )
          {
              $diadasemana = " 3 ";
          }
          if($_POST["diasemana"][0] == "qui" )
          {
              $diadasemana = " 4 ";
          }
          if($_POST["diasemana"][0] == "sex" )
          {
              $diadasemana = " 5 ";
          }
          if($_POST["diasemana"][0] == "sab" )
          {
              $diadasemana = " 6 ";
          }     
        }
        else
        { 
              for($i=0;$i<count($_POST["diasemana"]);$i++)
              {
                  if($_POST["diasemana"][$i] == "seg" )
                  {
                      $diadasemana .= " 1 ";
                  }
                  if($_POST["diasemana"][$i] == "ter" )
                  {
                      $diadasemana .= " 2 ";
                  }
                  if($_POST["diasemana"][$i] == "qua" )
                  {
                      $diadasemana .= " 3 ";
                  }
                  if($_POST["diasemana"][$i] == "qui" )
                  {
                      $diadasemana .= " 4 ";
                  }
                  if($_POST["diasemana"][$i] == "sex" )
                  {
                      $diadasemana .= " 5 ";
                  }
                  if($_POST["diasemana"][$i] == "sab" )
                  {
                      $diadasemana .= " 6 ";
                  }                                                                                                      
              }
        }
    
            $sql_instrutor = "SELECT 
            nomeInstrutor
            FROM instrutor 
            where cpfInstrutor like '%".$_POST['instrutor']."%' ";
        
                  
            $result_instrutor  = mysqli_query($conn, $sql_instrutor);
        
            while($row = mysqli_fetch_assoc($result_instrutor)) 
            {//MONTA A LISTA PARA EXIBIR NA TELA
               $nomeinstrutor    = $row["nomeInstrutor"];			     
            }   
            
            

            $diasparaconsulta = explode(" ",$diadasemana);

            if(count($diasparaconsulta) > 1)
            {
                for($d=0;$d<count($diasparaconsulta);$d++)
                {
                  if(trim($diasparaconsulta[$d]) != "")
                  {
                    $diasselformatado .= " replace(trim(diasdaSemana),\" \",\"\") like '%".trim($diasparaconsulta[$d])."%' or";
                  }
                }
            }
            else
            {
              $diasselformatado = "replace(trim(diasdaSemana),\" \",\"\") like '%".trim($diadasemana)."%' ";
            }
            
    
            $sql_checa_encavalamento = "
                select count(*) as quantasaulas from aula
                where cpfInstrutor = '".$_POST['instrutor']."' 
                and  ".$diasselformatado." and horarioInicio = STR_TO_DATE('01/01/2000 ".substr($_POST['horainicio'],0,2)."', '%d/%m/%Y %H') 
                and id_aula not in (".$_REQUEST['id'].") ";
   
       
             //VERIFICA SE O INSTRUTOR JÁ TEM AULA MARCADA NO INTERVALO DE HORÁRIO SELECIONADO NOS DIAS DA SEMANA SELECIONADOS              
            $result_encavalamento  = mysqli_query($conn, str_replace("or and","and",$sql_checa_encavalamento));
        
            while($rowe = mysqli_fetch_assoc($result_encavalamento)) 
            {//MONTA A LISTA PARA EXIBIR NA TELA
               $quantasaulas    = $rowe["quantasaulas"];			     
            }
    
            if($quantasaulas >0)
            {		
              $conn->close();		
              echo '<script>window.alert("Já existe Aula com este instrutor no intervalo de horário selecionado em um dos dias da semana selecionados .");</script>';
              echo '<script>history.back();</script>';
          
              exit;
              
            }	

				try
				{
					$sql_update = "update aula 
                  set 
                    cpfInstrutor = '".str_replace(".","",str_replace("-","",$_POST['instrutor']))."'
                  , nomeInstrutor_0 = '".mb_convert_case($nomeinstrutor, MB_CASE_LOWER, $encoding)."'
                  , horarioInicio = STR_TO_DATE('01/01/2000 ".$_POST["horainicio"]."', '%d/%m/%Y %H:%i') 
                  , horarioFim  = STR_TO_DATE('01/01/2000 ".$_POST["horafim"]."', '%d/%m/%Y %H:%i')                   
                  , diasDaSemana = '".$diadasemana."'
									where id_aula = ".$_REQUEST['id']." 
                  ";
                  

					if(mysqli_query($conn, $sql_update))
					{
						$conn->close();		
						echo '<script>window.alert("Aula '.$_POST['aula'].' atualizada com sucesso");</script>';
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

       $sql_checa_presencas = "select count(*) as quantaspresencas from presenca where id_aula = ".$_REQUEST['id'];

       //VERIFICA SE JÁ TEM PRESENÇAS DA AULA              
       $result_presenca  = mysqli_query($conn, $sql_checa_presencas);
    
       while($rowp = mysqli_fetch_assoc($result_presenca)) 
       {//MONTA A LISTA PARA EXIBIR NA TELA
          $quantaspresencas    = $rowp["quantaspresencas"];			     
       }

       if($quantaspresencas >0)
       {		
         $conn->close();		
         echo '<script>window.alert("Existem presenças desta aula .");</script>';
         echo '<script>history.back();</script>';
     
         exit;
         
       }	       


				try
				{
					$sql_update = "delete from aula 
									where id_aula = ".$_REQUEST['id']." 
					";


					if(mysqli_query($conn, $sql_update))
					{
						$conn->close();		
						echo '<script>window.alert("Aula excluída com sucesso");</script>';
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


$conn = mysqli_connect($servidor, $usuario, $senha, $banco);

//////PREPARA A LISTA DE INSTRUTORES

$cpf_instrutores= array();
$instrutores = array();

// Check connection
if (!$conn) 
{
      die("Falha na Conexão: " . mysqli_connect_error());
}
else
{
    $sql_instrutor = "SELECT 
     cpfInstrutor
    ,nomeInstrutor
    ,tipoAtividade
    FROM instrutor 
    order by 2 ";

          
    $result_instrutor  = mysqli_query($conn, $sql_instrutor);

    while($row = mysqli_fetch_assoc($result_instrutor)) 
    {//MONTA A LISTA PARA EXIBIR NA TELA
       $cpf_instrutores[]  = $row["cpfInstrutor"];			     
       $instrutores[]      = $row["nomeInstrutor"];			     
       $atividades[]       = $row["tipoAtividade"];	       
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
    <title>Academia de Gin&aacute;stica · Aula</title>

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
        <a class="nav-link" href="http://<?php echo $_SERVER["HTTP_HOST"];?>">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/back/1_Cliente/ConsultarCliente.html">Cliente</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/back/2_Instrutor/ConsultarInstrutor.html">Instrutor</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="/back/3_Aula/ConsultarAula.html">Aula<span class="sr-only">(current)</span></a>
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
    <?php //VERIFICA SE A TELA FOI CHAMADA PARA CADASTRO OU EDIÇÃO. SE FOR PARA EDIÇÃO JÁ CARREGA OS CAMPOS E BLOQUEIA O CHAVE CPF ?>
  <p class="h1"><?php if($_REQUEST['acao'] == 'edicao') { ?> Editar <?php } else {   ?> Cadastrar <?php } ?>Aula</p>
<?php 
if($_REQUEST['acao'] == 'edicao') 
{ 
?> 
  <form name="cadastro" action="aula.php?acao=atualizar" method="post" enctype="multipart/form-data" onSubmit="return verificaCamposAula();">
<?php  
}
else
{
?>
  <form name="cadastro" action="aula.php?acao=gravar" method="post" enctype="multipart/form-data" onSubmit="return verificaCamposAula();">	
<?php 
}
?>
    
  <div class="form-group">
    <label for="inputAula">Nome da Aula</label>
    <input type="text" class="form-control" id="aula" name="aula" maxlength="10" <?php if($aula!="") { ?> value="<?php echo $aula;?>" readonly <?php } ?> placeholder="Body Dance">
  </div>
  <div class="form-group">
    <label for="inputInicio">Horário do Início</label>
    <input type="text" class="form-control" id="horainicio" name="horainicio" maxlength="5" value="<?php echo $horainicio;?>" onkeypress="mascaraHora( this, event )" placeholder="12:34">
  </div>
  <div class="form-group">
    <label for="inputFim">Horário do Fim</label>
    <input type="text" class="form-control" id="horafim" name="horafim" maxlength="5" value="<?php echo $horafim;?>" onkeypress="mascaraHora( this, event )" placeholder="12:34">
  </div>
    
      <label class="form-check-label">
        Dia da Semana
      </label>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="seg" id="segunda" name="diasemana[]" <?php if($segunda == "s") { ?> checked <?php } ?> >
      <label class="form-check-label" for="diasemana">
        Segunda-Feira
      </label>
    </div>
      <div class="form-check">
      <input class="form-check-input" type="checkbox" value="ter" id="terca" name="diasemana[]" <?php if($terca == "s") { ?> checked <?php } ?> >
      <label class="form-check-label" for="diasemana">
        Terça-Feira
      </label>
    </div>
        <div class="form-check">
      <input class="form-check-input" type="checkbox" value="qua" id="quarta" name="diasemana[]" <?php if($quarta == "s") { ?> checked <?php } ?> >
      <label class="form-check-label" for="diasemana">
        Quarta-Feira
      </label>
    </div>
        <div class="form-check">
      <input class="form-check-input" type="checkbox" value="qui" id="quinta" name="diasemana[]" <?php if($quinta == "s") { ?> checked <?php } ?> >
      <label class="form-check-label" for="diasemana">
        Quinta-Feira
      </label>
    </div>
        <div class="form-check">
      <input class="form-check-input" type="checkbox" value="sex" id="sexta" name="diasemana[]" <?php if($sexta == "s") { ?> checked <?php } ?> >
      <label class="form-check-label" for="diasemana">
        Sexta-Feira
      </label>
    </div>
        <div class="form-check">
      <input class="form-check-input" type="checkbox" value="sab" id="sabado" name="diasemana[]"<?php if($sabado == "s") { ?> checked <?php } ?> >
      <label class="form-check-label" for="diasemana">
        Sabado
      </label>
    </div>
    
    <div class="input-group mb-3">
      <div class="input-group-prepend">
          <label class="input-group-text" for="inputGroupSelect01">Instrutor</label>
      </div>
      <select class="custom-select" id="instrutor" name="instrutor">
        <option value="">Selecione</option>
    <?php 
    for($i=0;$i<count($instrutores);$i++)
    {
    ?>
        <option value="<?php echo $cpf_instrutores[$i];?>" <?php if(trim($cpf_instrutores[$i]) == trim($instrutor_sel)) { ?> selected <?php } ?> >    
        <?php echo $instrutores[$i]." - Atividade = ".$atividades[$i]; ?>
        </option>
    <?php
    }
    ?>
    </select>
    </div>    
    <?php 
    if($_REQUEST['id'] != "") 
    {
    ?> 
    <input type="hidden" name="id" value="<?php echo $_REQUEST["id"];?>" >
    <?php
    }
    ?>
    <input class="btn btn-primary btn-lg" type="submit" value="Confirmar" >
  <a class="btn btn-secondary btn-lg" href="listaraula.php" role="button">Cancelar</a>

</form>
<div class="error-message" id="errosform" style=" display:none;"></div>	
    </div>
    </div>
</main><!-- /.container -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script></body>
</html>
