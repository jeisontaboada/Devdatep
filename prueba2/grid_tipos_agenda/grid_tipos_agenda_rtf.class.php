<?php

class grid_tipos_agenda_rtf
{
   var $Db;
   var $Erro;
   var $Ini;
   var $Lookup;
   var $nm_data;
   var $Texto_tag;
   var $Arquivo;
   var $Tit_doc;
   var $sc_proc_grid; 
   var $NM_cmp_hidden = array();

   //---- 
   function __construct()
   {
      $this->nm_data   = new nm_data("es");
      $this->Texto_tag = "";
   }


function actionBar_isValidState($buttonName, $buttonState)
{
    switch ($buttonName) {
    }

    return false;
}


function actionBar_displayState($buttonName)
{
    switch ($buttonName) {
    }
}

function actionBar_getStateHint($buttonName)
{
    switch ($buttonName) {
    }
}

function actionBar_getStateConfirm($buttonName)
{
    switch ($buttonName) {
    }
}

function actionBar_getStateDisable($buttonName)
{
    if (isset($this->sc_actionbar_disabled[$buttonName]) && $this->sc_actionbar_disabled[$buttonName]) {
        return ' disabled';
    }

    return '';
}

function actionBar_getStateHide($buttonName)
{
    if (isset($this->sc_actionbar_hidden[$buttonName]) && $this->sc_actionbar_hidden[$buttonName]) {
        return ' sc-actionbar-button-hidden';
    }

    return '';
}

   //---- 
   function monta_rtf()
   {
      $this->inicializa_vars();
      $this->gera_texto_tag();
      $this->grava_arquivo_rtf();
      if ($this->Ini->sc_export_ajax)
      {
          $this->Arr_result['file_export']  = NM_charset_to_utf8($this->Rtf_f);
          $this->Arr_result['title_export'] = NM_charset_to_utf8($this->Tit_doc);
          $Temp = ob_get_clean();
          if ($Temp !== false && trim($Temp) != "")
          {
              $this->Arr_result['htmOutput'] = NM_charset_to_utf8($Temp);
          }
          $oJson = new Services_JSON();
          echo $oJson->encode($this->Arr_result);
          exit;
      }
      else
      {
          $this->progress_bar_end();
      }
   }

   //----- 
   function inicializa_vars()
   {
      global $nm_lang;
      if (isset($GLOBALS['nmgp_parms']) && !empty($GLOBALS['nmgp_parms'])) 
      { 
          $GLOBALS['nmgp_parms'] = str_replace("@aspass@", "'", $GLOBALS['nmgp_parms']);
          $todox = str_replace("?#?@?@?", "?#?@ ?@?", $GLOBALS["nmgp_parms"]);
          $todo  = explode("?@?", $todox);
          foreach ($todo as $param)
          {
               $cadapar = explode("?#?", $param);
               if (1 < sizeof($cadapar))
               {
                   if (substr($cadapar[0], 0, 11) == "SC_glo_par_")
                   {
                       $cadapar[0] = substr($cadapar[0], 11);
                       $cadapar[1] = $_SESSION[$cadapar[1]];
                   }
                   if (isset($GLOBALS['sc_conv_var'][$cadapar[0]]))
                   {
                       $cadapar[0] = $GLOBALS['sc_conv_var'][$cadapar[0]];
                   }
                   elseif (isset($GLOBALS['sc_conv_var'][strtolower($cadapar[0])]))
                   {
                       $cadapar[0] = $GLOBALS['sc_conv_var'][strtolower($cadapar[0])];
                   }
                   nm_limpa_str_grid_tipos_agenda($cadapar[1]);
                   nm_protect_num_grid_tipos_agenda($cadapar[0], $cadapar[1]);
                   if ($cadapar[1] == "@ ") {$cadapar[1] = trim($cadapar[1]); }
                   $Tmp_par   = $cadapar[0];
                   $$Tmp_par = $cadapar[1];
                   if ($Tmp_par == "nmgp_opcao")
                   {
                       $_SESSION['sc_session'][$script_case_init]['grid_tipos_agenda']['opcao'] = $cadapar[1];
                   }
               }
          }
      }
      if (isset($arr_seleccionados)) 
      {
          $_SESSION['arr_seleccionados'] = $arr_seleccionados;
          nm_limpa_str_grid_tipos_agenda($_SESSION["arr_seleccionados"]);
      }
      if (isset($total_duracion)) 
      {
          $_SESSION['total_duracion'] = $total_duracion;
          nm_limpa_str_grid_tipos_agenda($_SESSION["total_duracion"]);
      }
      if (isset($total_valor)) 
      {
          $_SESSION['total_valor'] = $total_valor;
          nm_limpa_str_grid_tipos_agenda($_SESSION["total_valor"]);
      }
      if (isset($i)) 
      {
          $_SESSION['i'] = $i;
          nm_limpa_str_grid_tipos_agenda($_SESSION["i"]);
      }
      if (isset($glo_cliente_id)) 
      {
          $_SESSION['glo_cliente_id'] = $glo_cliente_id;
          nm_limpa_str_grid_tipos_agenda($_SESSION["glo_cliente_id"]);
      }
      $dir_raiz          = strrpos($_SERVER['PHP_SELF'],"/") ;  
      $dir_raiz          = substr($_SERVER['PHP_SELF'], 0, $dir_raiz + 1) ;  
      $this->nm_location = $this->Ini->sc_protocolo . $this->Ini->server . $dir_raiz; 
      require_once($this->Ini->path_aplicacao . "grid_tipos_agenda_total.class.php"); 
      $this->Tot      = new grid_tipos_agenda_total($this->Ini->sc_page);
      $this->prep_modulos("Tot");
      $Gb_geral = "quebra_geral_" . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['SC_Ind_Groupby'];
      if (method_exists($this->Tot,$Gb_geral))
      {
          $this->Tot->$Gb_geral();
          $this->count_ger = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['tot_geral'][1];
      }
      if (!$this->Ini->sc_export_ajax) {
          require_once($this->Ini->path_lib_php . "/sc_progress_bar.php");
          $this->pb = new scProgressBar();
          $this->pb->setRoot($this->Ini->root);
          $this->pb->setDir($_SESSION['scriptcase']['grid_tipos_agenda']['glo_nm_path_imag_temp'] . "/");
          $this->pb->setProgressbarMd5($_GET['pbmd5']);
          $this->pb->initialize();
          $this->pb->setReturnUrl("./");
          $this->pb->setReturnOption('volta_grid');
          $this->pb->setTotalSteps($this->count_ger);
      }
      $this->Arquivo    = "sc_rtf";
      $this->Arquivo   .= "_" . date("YmdHis") . "_" . rand(0, 1000);
      $this->Arquivo   .= "_grid_tipos_agenda";
      $this->Arquivo   .= ".rtf";
      $this->Tit_doc    = "grid_tipos_agenda.rtf";
   }
   //---- 
   function prep_modulos($modulo)
   {
      $this->$modulo->Ini    = $this->Ini;
      $this->$modulo->Db     = $this->Db;
      $this->$modulo->Erro   = $this->Erro;
      $this->$modulo->Lookup = $this->Lookup;
   }


   //----- 
   function gera_texto_tag()
   {
     global $nm_lang;
      global $nm_nada, $nm_lang;

      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->sc_proc_grid = false; 
      $nm_raiz_img  = ""; 
      if (isset($_SESSION['scriptcase']['sc_apl_conf']['grid_tipos_agenda']['field_display']) && !empty($_SESSION['scriptcase']['sc_apl_conf']['grid_tipos_agenda']['field_display']))
      {
          foreach ($_SESSION['scriptcase']['sc_apl_conf']['grid_tipos_agenda']['field_display'] as $NM_cada_field => $NM_cada_opc)
          {
              $this->NM_cmp_hidden[$NM_cada_field] = $NM_cada_opc;
          }
      }
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['usr_cmp_sel']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['usr_cmp_sel']))
      {
          foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['usr_cmp_sel'] as $NM_cada_field => $NM_cada_opc)
          {
              $this->NM_cmp_hidden[$NM_cada_field] = $NM_cada_opc;
          }
      }
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['php_cmp_sel']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['php_cmp_sel']))
      {
          foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['php_cmp_sel'] as $NM_cada_field => $NM_cada_opc)
          {
              $this->NM_cmp_hidden[$NM_cada_field] = $NM_cada_opc;
          }
      }
      $this->sc_where_orig   = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['where_orig'];
      $this->sc_where_atual  = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['where_pesq'];
      $this->sc_where_filtro = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['where_pesq_filtro'];
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['campos_busca']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['campos_busca']))
      { 
          $Busca_temp = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['campos_busca'];
          if ($_SESSION['scriptcase']['charset'] != "UTF-8")
          {
              $Busca_temp = NM_conv_charset($Busca_temp, $_SESSION['scriptcase']['charset'], "UTF-8");
          }
          $this->tipo_duracion = (isset($Busca_temp['tipo_duracion'])) ? $Busca_temp['tipo_duracion'] : ""; 
          $tmp_pos = (is_string($this->tipo_duracion)) ? strpos($this->tipo_duracion, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->tipo_duracion))
          {
              $this->tipo_duracion = substr($this->tipo_duracion, 0, $tmp_pos);
          }
          $this->id_tipos_agenda = (isset($Busca_temp['id_tipos_agenda'])) ? $Busca_temp['id_tipos_agenda'] : ""; 
          $tmp_pos = (is_string($this->id_tipos_agenda)) ? strpos($this->id_tipos_agenda, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->id_tipos_agenda))
          {
              $this->id_tipos_agenda = substr($this->id_tipos_agenda, 0, $tmp_pos);
          }
          $this->descripcion = (isset($Busca_temp['descripcion'])) ? $Busca_temp['descripcion'] : ""; 
          $tmp_pos = (is_string($this->descripcion)) ? strpos($this->descripcion, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->descripcion))
          {
              $this->descripcion = substr($this->descripcion, 0, $tmp_pos);
          }
          $this->duracion_mostrada = (isset($Busca_temp['duracion_mostrada'])) ? $Busca_temp['duracion_mostrada'] : ""; 
          $tmp_pos = (is_string($this->duracion_mostrada)) ? strpos($this->duracion_mostrada, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->duracion_mostrada))
          {
              $this->duracion_mostrada = substr($this->duracion_mostrada, 0, $tmp_pos);
          }
      } 
      $this->nm_where_dinamico = "";
      $_SESSION['scriptcase']['grid_tipos_agenda']['contr_erro'] = 'on';
if (!isset($_SESSION['i'])) {$_SESSION['i'] = "";}
if (!isset($this->sc_temp_i)) {$this->sc_temp_i = (isset($_SESSION['i'])) ? $_SESSION['i'] : "";}
if (!isset($_SESSION['total_valor'])) {$_SESSION['total_valor'] = "";}
if (!isset($this->sc_temp_total_valor)) {$this->sc_temp_total_valor = (isset($_SESSION['total_valor'])) ? $_SESSION['total_valor'] : "";}
if (!isset($_SESSION['total_duracion'])) {$_SESSION['total_duracion'] = "";}
if (!isset($this->sc_temp_total_duracion)) {$this->sc_temp_total_duracion = (isset($_SESSION['total_duracion'])) ? $_SESSION['total_duracion'] : "";}
if (!isset($_SESSION['arr_seleccionados'])) {$_SESSION['arr_seleccionados'] = "";}
if (!isset($this->sc_temp_arr_seleccionados)) {$this->sc_temp_arr_seleccionados = (isset($_SESSION['arr_seleccionados'])) ? $_SESSION['arr_seleccionados'] : "";}
  unset($this->sc_temp_arr_seleccionados);
$this->sc_temp_arr_seleccionados=array();
$this->sc_temp_i=0;
$this->sc_temp_total_duracion=0;
$this->sc_temp_total_valor=0.0;
$this->mensaje("El proceso de una petición de servicios, comienza con la elección de los trabajos");
if (isset($this->sc_temp_arr_seleccionados)) {$_SESSION['arr_seleccionados'] = $this->sc_temp_arr_seleccionados;}
if (isset($this->sc_temp_total_duracion)) {$_SESSION['total_duracion'] = $this->sc_temp_total_duracion;}
if (isset($this->sc_temp_total_valor)) {$_SESSION['total_valor'] = $this->sc_temp_total_valor;}
if (isset($this->sc_temp_i)) {$_SESSION['i'] = $this->sc_temp_i;}
$_SESSION['scriptcase']['grid_tipos_agenda']['contr_erro'] = 'off'; 
      if  (!empty($this->nm_where_dinamico)) 
      {   
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['where_pesq'] .= $this->nm_where_dinamico;
      }   
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['rtf_name']))
      {
          $Pos = strrpos($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['rtf_name'], ".");
          if ($Pos === false) {
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['rtf_name'] .= ".rtf";
          }
          $this->Arquivo = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['rtf_name'];
          $this->Tit_doc = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['rtf_name'];
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['rtf_name']);
      }
      $this->arr_export = array('label' => array(), 'lines' => array());
      $this->arr_span   = array();

      $this->Texto_tag .= "<table>\r\n";
      $this->Texto_tag .= "<tr>\r\n";
      foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['field_order'] as $Cada_col)
      { 
          $SC_Label = (isset($this->New_label['descripcion'])) ? $this->New_label['descripcion'] : "Descripcion"; 
          if ($Cada_col == "descripcion" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
              $SC_Label = NM_charset_to_utf8($SC_Label);
              $SC_Label = str_replace('<', '&lt;', $SC_Label);
              $SC_Label = str_replace('>', '&gt;', $SC_Label);
              $this->Texto_tag .= "<td>" . $SC_Label . "</td>\r\n";
          }
          $SC_Label = (isset($this->New_label['duracion'])) ? $this->New_label['duracion'] : "Duracion"; 
          if ($Cada_col == "duracion" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
              $SC_Label = NM_charset_to_utf8($SC_Label);
              $SC_Label = str_replace('<', '&lt;', $SC_Label);
              $SC_Label = str_replace('>', '&gt;', $SC_Label);
              $this->Texto_tag .= "<td>" . $SC_Label . "</td>\r\n";
          }
          $SC_Label = (isset($this->New_label['valor'])) ? $this->New_label['valor'] : "Valor"; 
          if ($Cada_col == "valor" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
              $SC_Label = NM_charset_to_utf8($SC_Label);
              $SC_Label = str_replace('<', '&lt;', $SC_Label);
              $SC_Label = str_replace('>', '&gt;', $SC_Label);
              $this->Texto_tag .= "<td>" . $SC_Label . "</td>\r\n";
          }
          $SC_Label = (isset($this->New_label['id_tipos_agenda'])) ? $this->New_label['id_tipos_agenda'] : "Id Tipos Agenda"; 
          if ($Cada_col == "id_tipos_agenda" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
              $SC_Label = NM_charset_to_utf8($SC_Label);
              $SC_Label = str_replace('<', '&lt;', $SC_Label);
              $SC_Label = str_replace('>', '&gt;', $SC_Label);
              $this->Texto_tag .= "<td>" . $SC_Label . "</td>\r\n";
          }
          $SC_Label = (isset($this->New_label['duracion_mostrada'])) ? $this->New_label['duracion_mostrada'] : "Duracion Mostrada"; 
          if ($Cada_col == "duracion_mostrada" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
              $SC_Label = NM_charset_to_utf8($SC_Label);
              $SC_Label = str_replace('<', '&lt;', $SC_Label);
              $SC_Label = str_replace('>', '&gt;', $SC_Label);
              $this->Texto_tag .= "<td>" . $SC_Label . "</td>\r\n";
          }
          $SC_Label = (isset($this->New_label['tipo_duracion'])) ? $this->New_label['tipo_duracion'] : "Tipo Duracion"; 
          if ($Cada_col == "tipo_duracion" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
              $SC_Label = NM_charset_to_utf8($SC_Label);
              $SC_Label = str_replace('<', '&lt;', $SC_Label);
              $SC_Label = str_replace('>', '&gt;', $SC_Label);
              $this->Texto_tag .= "<td>" . $SC_Label . "</td>\r\n";
          }
      } 
      $this->Texto_tag .= "</tr>\r\n";
      $this->nm_field_dinamico = array();
      $this->nm_order_dinamico = array();
      $nmgp_select_count = "SELECT count(*) AS countTest from " . $this->Ini->nm_tabela; 
      if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_sybase))
      { 
          $nmgp_select = "SELECT descripcion, duracion, valor, id_tipos_agenda, duracion_mostrada, tipo_duracion from " . $this->Ini->nm_tabela; 
      } 
      elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_mysql))
      { 
          $nmgp_select = "SELECT descripcion, duracion, valor, id_tipos_agenda, duracion_mostrada, tipo_duracion from " . $this->Ini->nm_tabela; 
      } 
      elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_mssql))
      { 
       $nmgp_select = "SELECT descripcion, duracion, valor, id_tipos_agenda, duracion_mostrada, tipo_duracion from " . $this->Ini->nm_tabela; 
      } 
      elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_oracle))
      { 
          $nmgp_select = "SELECT descripcion, duracion, valor, id_tipos_agenda, duracion_mostrada, tipo_duracion from " . $this->Ini->nm_tabela; 
      } 
      elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_informix))
      { 
          $nmgp_select = "SELECT descripcion, duracion, valor, id_tipos_agenda, duracion_mostrada, tipo_duracion from " . $this->Ini->nm_tabela; 
      } 
      else 
      { 
          $nmgp_select = "SELECT descripcion, duracion, valor, id_tipos_agenda, duracion_mostrada, tipo_duracion from " . $this->Ini->nm_tabela; 
      } 
      $nmgp_select .= " " . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['where_pesq'];
      $nmgp_select_count .= " " . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['where_pesq'];
      $nmgp_order_by = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['order_grid'];
      $nmgp_select .= $nmgp_order_by; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nmgp_select_count;
      $rt = $this->Db->Execute($nmgp_select_count);
      if ($rt === false && !$rt->EOF && $GLOBALS["NM_ERRO_IBASE"] != 1)
      {
         $this->Erro->mensagem(__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg());
         exit;
      }
      $this->count_ger = $rt->fields[0];
      $rt->Close();
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nmgp_select;
      $rs = $this->Db->Execute($nmgp_select);
      if ($rs === false && !$rs->EOF && $GLOBALS["NM_ERRO_IBASE"] != 1)
      {
         $this->Erro->mensagem(__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg());
         exit;
      }
      $this->SC_seq_register = 0;
      $PB_tot = (isset($this->count_ger) && $this->count_ger > 0) ? "/" . $this->count_ger : "";
      while (!$rs->EOF)
      {
         $this->SC_seq_register++;
         if (!$this->Ini->sc_export_ajax) {
             $Mens_bar = NM_charset_to_utf8($this->Ini->Nm_lang['lang_othr_prcs']);
             $this->pb->setProgressbarMessage($Mens_bar . ": " . $this->SC_seq_register . $PB_tot);
             $this->pb->addSteps(1);
         }
         $this->Texto_tag .= "<tr>\r\n";
         $this->descripcion = $rs->fields[0] ;  
         $this->duracion = $rs->fields[1] ;  
         $this->duracion = (string)$this->duracion;
         $this->valor = $rs->fields[2] ;  
         $this->valor =  str_replace(",", ".", $this->valor);
         $this->valor = (string)$this->valor;
         $this->id_tipos_agenda = $rs->fields[3] ;  
         $this->id_tipos_agenda = (string)$this->id_tipos_agenda;
         $this->duracion_mostrada = $rs->fields[4] ;  
         $this->duracion_mostrada = (string)$this->duracion_mostrada;
         $this->tipo_duracion = $rs->fields[5] ;  
         $this->sc_proc_grid = true; 
         foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['field_order'] as $Cada_col)
         { 
            if (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off")
            { 
                $NM_func_exp = "NM_export_" . $Cada_col;
                $this->$NM_func_exp();
            } 
         } 
         $this->Texto_tag .= "</tr>\r\n";
         $rs->MoveNext();
      }
      $this->Texto_tag .= "</table>\r\n";
      if(isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['export_sel_columns']['field_order']))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['field_order'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['export_sel_columns']['field_order'];
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['export_sel_columns']['field_order']);
      }
      if(isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['export_sel_columns']['usr_cmp_sel']))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['usr_cmp_sel'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['export_sel_columns']['usr_cmp_sel'];
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['export_sel_columns']['usr_cmp_sel']);
      }
      $rs->Close();
   }
   //----- descripcion
   function NM_export_descripcion()
   {
         $this->descripcion = html_entity_decode($this->descripcion, ENT_COMPAT, $_SESSION['scriptcase']['charset']);
         $this->descripcion = strip_tags($this->descripcion);
         $this->descripcion = NM_charset_to_utf8($this->descripcion);
         $this->descripcion = str_replace('<', '&lt;', $this->descripcion);
         $this->descripcion = str_replace('>', '&gt;', $this->descripcion);
         $this->Texto_tag .= "<td>" . $this->descripcion . "</td>\r\n";
   }
   //----- duracion
   function NM_export_duracion()
   {
             nmgp_Form_Num_Val($this->duracion, $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "2", "", "N:" . $_SESSION['scriptcase']['reg_conf']['neg_num'] , $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['num_group_digit']) ; 
         $this->duracion = NM_charset_to_utf8($this->duracion);
         $this->duracion = str_replace('<', '&lt;', $this->duracion);
         $this->duracion = str_replace('>', '&gt;', $this->duracion);
         $this->Texto_tag .= "<td>" . $this->duracion . "</td>\r\n";
   }
   //----- valor
   function NM_export_valor()
   {
             nmgp_Form_Num_Val($this->valor, $_SESSION['scriptcase']['reg_conf']['grup_val'], $_SESSION['scriptcase']['reg_conf']['dec_val'], "2", "S", "2", "", "V:" . $_SESSION['scriptcase']['reg_conf']['monet_f_pos'] . ":" . $_SESSION['scriptcase']['reg_conf']['monet_f_neg'], $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['unid_mont_group_digit']) ; 
         $this->valor = NM_charset_to_utf8($this->valor);
         $this->valor = str_replace('<', '&lt;', $this->valor);
         $this->valor = str_replace('>', '&gt;', $this->valor);
         $this->Texto_tag .= "<td>" . $this->valor . "</td>\r\n";
   }
   //----- id_tipos_agenda
   function NM_export_id_tipos_agenda()
   {
             nmgp_Form_Num_Val($this->id_tipos_agenda, $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "2", "", "N:" . $_SESSION['scriptcase']['reg_conf']['neg_num'] , $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['num_group_digit']) ; 
         $this->id_tipos_agenda = NM_charset_to_utf8($this->id_tipos_agenda);
         $this->id_tipos_agenda = str_replace('<', '&lt;', $this->id_tipos_agenda);
         $this->id_tipos_agenda = str_replace('>', '&gt;', $this->id_tipos_agenda);
         $this->Texto_tag .= "<td>" . $this->id_tipos_agenda . "</td>\r\n";
   }
   //----- duracion_mostrada
   function NM_export_duracion_mostrada()
   {
             nmgp_Form_Num_Val($this->duracion_mostrada, $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "2", "", "N:" . $_SESSION['scriptcase']['reg_conf']['neg_num'] , $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['num_group_digit']) ; 
         $this->duracion_mostrada = NM_charset_to_utf8($this->duracion_mostrada);
         $this->duracion_mostrada = str_replace('<', '&lt;', $this->duracion_mostrada);
         $this->duracion_mostrada = str_replace('>', '&gt;', $this->duracion_mostrada);
         $this->Texto_tag .= "<td>" . $this->duracion_mostrada . "</td>\r\n";
   }
   //----- tipo_duracion
   function NM_export_tipo_duracion()
   {
         $this->tipo_duracion = html_entity_decode($this->tipo_duracion, ENT_COMPAT, $_SESSION['scriptcase']['charset']);
         $this->tipo_duracion = strip_tags($this->tipo_duracion);
         $this->tipo_duracion = NM_charset_to_utf8($this->tipo_duracion);
         $this->tipo_duracion = str_replace('<', '&lt;', $this->tipo_duracion);
         $this->tipo_duracion = str_replace('>', '&gt;', $this->tipo_duracion);
         $this->Texto_tag .= "<td>" . $this->tipo_duracion . "</td>\r\n";
   }

   //----- 
   function grava_arquivo_rtf()
   {
      global $nm_lang, $doc_wrap;
      $this->Rtf_f = $this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arquivo;
      $rtf_f       = fopen($this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arquivo, "w");
      require_once($this->Ini->path_third      . "/rtf_new/document_generator/cl_xml2driver.php"); 
      $text_ok  =  "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n"; 
      $text_ok .=  "<DOC config_file=\"" . $this->Ini->path_third . "/rtf_new/doc_config.inc\" >\r\n"; 
      $text_ok .=  $this->Texto_tag; 
      $text_ok .=  "</DOC>\r\n"; 
      $xml = new nDOCGEN($text_ok,"RTF"); 
      fwrite($rtf_f, $xml->get_result_file());
      fclose($rtf_f);
   }

   function nm_conv_data_db($dt_in, $form_in, $form_out)
   {
       $dt_out = $dt_in;
       if (strtoupper($form_in) == "DB_FORMAT") {
           if ($dt_out == "null" || $dt_out == "")
           {
               $dt_out = "";
               return $dt_out;
           }
           $form_in = "AAAA-MM-DD";
       }
       if (strtoupper($form_out) == "DB_FORMAT") {
           if (empty($dt_out))
           {
               $dt_out = "null";
               return $dt_out;
           }
           $form_out = "AAAA-MM-DD";
       }
       if (strtoupper($form_out) == "SC_FORMAT_REGION") {
           $this->nm_data->SetaData($dt_in, strtoupper($form_in));
           $prep_out  = (strpos(strtolower($form_in), "dd") !== false) ? "dd" : "";
           $prep_out .= (strpos(strtolower($form_in), "mm") !== false) ? "mm" : "";
           $prep_out .= (strpos(strtolower($form_in), "aa") !== false) ? "aaaa" : "";
           $prep_out .= (strpos(strtolower($form_in), "yy") !== false) ? "aaaa" : "";
           return $this->nm_data->FormataSaida($this->nm_data->FormatRegion("DT", $prep_out));
       }
       else {
           nm_conv_form_data($dt_out, $form_in, $form_out);
           return $dt_out;
       }
   }
   function progress_bar_end()
   {
      unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['rtf_file']);
      if (is_file($this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arquivo))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['rtf_file'] = $this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arquivo;
      }
      $path_doc_md5 = md5($this->Ini->path_imag_temp . "/" . $this->Arquivo);
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda'][$path_doc_md5][0] = $this->Ini->path_imag_temp . "/" . $this->Arquivo;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda'][$path_doc_md5][1] = $this->Tit_doc;
      $Mens_bar = $this->Ini->Nm_lang['lang_othr_file_msge'];
      if ($_SESSION['scriptcase']['charset'] != "UTF-8") {
          $Mens_bar = sc_convert_encoding($Mens_bar, "UTF-8", $_SESSION['scriptcase']['charset']);
      }
      $this->pb->setProgressbarMessage($Mens_bar);
      $this->pb->setDownloadLink($this->Ini->path_imag_temp . "/" . $this->Arquivo);
      $this->pb->setDownloadMd5($path_doc_md5);
      $this->pb->completed();
   }
   //---- 
   function monta_html()
   {
      global $nm_url_saida, $nm_lang;
      include($this->Ini->path_btn . $this->Ini->Str_btn_grid);
      unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['rtf_file']);
      if (is_file($this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arquivo))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda']['rtf_file'] = $this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arquivo;
      }
      $path_doc_md5 = md5($this->Ini->path_imag_temp . "/" . $this->Arquivo);
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda'][$path_doc_md5][0] = $this->Ini->path_imag_temp . "/" . $this->Arquivo;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tipos_agenda'][$path_doc_md5][1] = $this->Tit_doc;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML<?php echo $_SESSION['scriptcase']['reg_conf']['html_dir'] ?>>
<HEAD>
 <TITLE><?php echo $this->Ini->Nm_lang['lang_othr_grid_title'] ?> tipos_agenda :: RTF</TITLE>
 <META http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['scriptcase']['charset_html'] ?>" />
<?php
if ($_SESSION['scriptcase']['proc_mobile'])
{
?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<?php
}
?>
  <META http-equiv="Expires" content="Fri, Jan 01 1900 00:00:00 GMT"/>
  <META http-equiv="Last-Modified" content="<?php echo gmdate("D, d M Y H:i:s"); ?> GMT"/>
  <META http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate"/>
  <META http-equiv="Cache-Control" content="post-check=0, pre-check=0"/>
  <META http-equiv="Pragma" content="no-cache"/>
 <link rel="shortcut icon" href="../_lib/img/scriptcase__NM__ico__NM__favicon.ico">
  <link rel="stylesheet" type="text/css" href="../_lib/css/<?php echo $this->Ini->str_schema_all ?>_export.css" /> 
  <link rel="stylesheet" type="text/css" href="../_lib/css/<?php echo $this->Ini->str_schema_all ?>_export<?php echo $_SESSION['scriptcase']['reg_conf']['css_dir'] ?>.css" /> 
 <?php
 if(isset($this->Ini->str_google_fonts) && !empty($this->Ini->str_google_fonts))
 {
 ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->Ini->str_google_fonts ?>" />
 <?php
 }
 ?>
  <link rel="stylesheet" type="text/css" href="../_lib/buttons/<?php echo $this->Ini->Str_btn_css ?>" /> 
</HEAD>
<BODY class="scExportPage">
<?php echo $this->Ini->Ajax_result_set ?>
<table style="border-collapse: collapse; border-width: 0; height: 100%; width: 100%"><tr><td style="padding: 0; text-align: center; vertical-align: middle">
 <table class="scExportTable" align="center">
  <tr>
   <td class="scExportTitle" style="height: 25px">RTF</td>
  </tr>
  <tr>
   <td class="scExportLine" style="width: 100%">
    <table style="border-collapse: collapse; border-width: 0; width: 100%"><tr><td class="scExportLineFont" style="padding: 3px 0 0 0" id="idMessage">
    <?php echo $this->Ini->Nm_lang['lang_othr_file_msge'] ?>
    </td><td class="scExportLineFont" style="text-align:right; padding: 3px 0 0 0">
     <?php echo nmButtonOutput($this->arr_buttons, "bexportview", "document.Fview.submit()", "document.Fview.submit()", "idBtnView", "", "", "", "", "", "", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "", "", "", "", "", "");
 ?>
     <?php echo nmButtonOutput($this->arr_buttons, "bdownload", "document.Fdown.submit()", "document.Fdown.submit()", "idBtnDown", "", "", "", "", "", "", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "", "", "", "", "", "");
 ?>
     <?php echo nmButtonOutput($this->arr_buttons, "bvoltar", "document.F0.submit()", "document.F0.submit()", "idBtnBack", "", "", "", "", "", "", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "", "", "", "", "", "");
 ?>
    </td></tr></table>
   </td>
  </tr>
 </table>
</td></tr></table>
<form name="Fview" method="get" action="<?php echo $this->Ini->path_imag_temp . "/" . $this->Arquivo ?>" target="_blank" style="display: none"> 
</form>
<form name="Fdown" method="get" action="grid_tipos_agenda_download.php" target="_blank" style="display: none"> 
<input type="hidden" name="script_case_init" value="<?php echo NM_encode_input($this->Ini->sc_page); ?>"> 
<input type="hidden" name="nm_tit_doc" value="grid_tipos_agenda"> 
<input type="hidden" name="nm_name_doc" value="<?php echo $path_doc_md5 ?>"> 
</form>
<FORM name="F0" method=post action="./"> 
<INPUT type="hidden" name="script_case_init" value="<?php echo NM_encode_input($this->Ini->sc_page); ?>"> 
<INPUT type="hidden" name="nmgp_opcao" value="volta_grid"> 
</FORM> 
</BODY>
</HTML>
<?php
   }
   function nm_gera_mask(&$nm_campo, $nm_mask)
   { 
      $trab_campo = $nm_campo;
      $trab_mask  = $nm_mask;
      $tam_campo  = strlen($nm_campo);
      $trab_saida = "";
      $str_highlight_ini = "";
      $str_highlight_fim = "";
      if(substr($nm_campo, 0, 23) == '<div class="highlight">' && substr($nm_campo, -6) == '</div>')
      {
           $str_highlight_ini = substr($nm_campo, 0, 23);
           $str_highlight_fim = substr($nm_campo, -6);

           $trab_campo = substr($nm_campo, 23, -6);
           $tam_campo  = strlen($trab_campo);
      }      $mask_num = false;
      for ($x=0; $x < strlen($trab_mask); $x++)
      {
          if (substr($trab_mask, $x, 1) == "#")
          {
              $mask_num = true;
              break;
          }
      }
      if ($mask_num )
      {
          $ver_duas = explode(";", $trab_mask);
          if (isset($ver_duas[1]) && !empty($ver_duas[1]))
          {
              $cont1 = count(explode("#", $ver_duas[0])) - 1;
              $cont2 = count(explode("#", $ver_duas[1])) - 1;
              if ($tam_campo >= $cont2)
              {
                  $trab_mask = $ver_duas[1];
              }
              else
              {
                  $trab_mask = $ver_duas[0];
              }
          }
          $tam_mask = strlen($trab_mask);
          $xdados = 0;
          for ($x=0; $x < $tam_mask; $x++)
          {
              if (substr($trab_mask, $x, 1) == "#" && $xdados < $tam_campo)
              {
                  $trab_saida .= substr($trab_campo, $xdados, 1);
                  $xdados++;
              }
              elseif ($xdados < $tam_campo)
              {
                  $trab_saida .= substr($trab_mask, $x, 1);
              }
          }
          if ($xdados < $tam_campo)
          {
              $trab_saida .= substr($trab_campo, $xdados);
          }
          $nm_campo = $str_highlight_ini . $trab_saida . $str_highlight_ini;
          return;
      }
      for ($ix = strlen($trab_mask); $ix > 0; $ix--)
      {
           $char_mask = substr($trab_mask, $ix - 1, 1);
           if ($char_mask != "x" && $char_mask != "z")
           {
               $trab_saida = $char_mask . $trab_saida;
           }
           else
           {
               if ($tam_campo != 0)
               {
                   $trab_saida = substr($trab_campo, $tam_campo - 1, 1) . $trab_saida;
                   $tam_campo--;
               }
               else
               {
                   $trab_saida = "0" . $trab_saida;
               }
           }
      }
      if ($tam_campo != 0)
      {
          $trab_saida = substr($trab_campo, 0, $tam_campo) . $trab_saida;
          $trab_mask  = str_repeat("z", $tam_campo) . $trab_mask;
      }
   
      $iz = 0; 
      for ($ix = 0; $ix < strlen($trab_mask); $ix++)
      {
           $char_mask = substr($trab_mask, $ix, 1);
           if ($char_mask != "x" && $char_mask != "z")
           {
               if ($char_mask == "." || $char_mask == ",")
               {
                   $trab_saida = substr($trab_saida, 0, $iz) . substr($trab_saida, $iz + 1);
               }
               else
               {
                   $iz++;
               }
           }
           elseif ($char_mask == "x" || substr($trab_saida, $iz, 1) != "0")
           {
               $ix = strlen($trab_mask) + 1;
           }
           else
           {
               $trab_saida = substr($trab_saida, 0, $iz) . substr($trab_saida, $iz + 1);
           }
      }
      $nm_campo = $str_highlight_ini . $trab_saida . $str_highlight_ini;
   } 
function saludo($parametro) {
$_SESSION['scriptcase']['grid_tipos_agenda']['contr_erro'] = 'on';
  
    echo "Hola mi amigo, $parametro";

$_SESSION['scriptcase']['grid_tipos_agenda']['contr_erro'] = 'off';
}
function mensaje($par_mensaje = 'mensaje') {
$_SESSION['scriptcase']['grid_tipos_agenda']['contr_erro'] = 'on';
  
    $cad = "<div id='caja_mensaje' ";
    $cad .= "style='background-color: darkgrey; color: white; width: 600px; margin: 0 auto; ";
    $cad .= "padding: 2px; margin: 10px auto; border: 1px solid black; border-radius: 20px; text-align: center;'>";
    $cad .= "<h2>";
    $cad .= $par_mensaje;
    $cad .= "</h2>";
    $cad .= "</div>";
    echo $cad;

$_SESSION['scriptcase']['grid_tipos_agenda']['contr_erro'] = 'off';
}
}

?>
