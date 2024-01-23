<?php

class grid_customer_ticket_flow_xml
{
   var $Db;
   var $Erro;
   var $Ini;
   var $Lookup;
   var $nm_data;

   var $Arquivo;
   var $Arquivo_view;
   var $Tit_doc;
   var $sc_proc_grid; 
   var $NM_cmp_hidden = array();
   var $count_ger;

   //---- 
   function __construct()
   {
      $this->nm_data   = new nm_data("en_us");
   }


function actionBar_isValidState($buttonName, $buttonState)
{
    return false;
}

   //---- 
   function monta_xml()
   {
      $this->inicializa_vars();
      $this->grava_arquivo();
      if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['embutida'])
      {
          if ($this->Ini->sc_export_ajax)
          {
              $this->Arr_result['file_export']  = NM_charset_to_utf8($this->Xml_f);
              $this->Arr_result['title_export'] = NM_charset_to_utf8($this->Tit_doc);
              $Temp = ob_get_clean();
              $oJson = new Services_JSON();
              echo $oJson->encode($this->Arr_result);
              exit;
          }
          else
          {
              $this->progress_bar_end();
          }
      }
      else
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['opcao'] = "";
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
                   nm_limpa_str_grid_customer_ticket_flow($cadapar[1]);
                   nm_protect_num_grid_customer_ticket_flow($cadapar[0], $cadapar[1]);
                   if ($cadapar[1] == "@ ") {$cadapar[1] = trim($cadapar[1]); }
                   $Tmp_par   = $cadapar[0];
                   $$Tmp_par = $cadapar[1];
                   if ($Tmp_par == "nmgp_opcao")
                   {
                       $_SESSION['sc_session'][$script_case_init]['grid_customer_ticket_flow']['opcao'] = $cadapar[1];
                   }
               }
          }
      }
      if (isset($v_ticketid)) 
      {
          $_SESSION['v_ticketid'] = $v_ticketid;
          nm_limpa_str_grid_customer_ticket_flow($_SESSION["v_ticketid"]);
      }
      if (isset($v_trackingid)) 
      {
          $_SESSION['v_trackingid'] = $v_trackingid;
          nm_limpa_str_grid_customer_ticket_flow($_SESSION["v_trackingid"]);
      }
      if (isset($attr_ticket_status) && isset($NM_contr_var_session) && $NM_contr_var_session == "Yes") 
      {
          $_SESSION['attr_ticket_status'] = $attr_ticket_status;
          nm_limpa_str_grid_customer_ticket_flow($_SESSION["attr_ticket_status"]);
      }
      if (isset($attr_tracking_id) && isset($NM_contr_var_session) && $NM_contr_var_session == "Yes") 
      {
          $_SESSION['attr_tracking_id'] = $attr_tracking_id;
          nm_limpa_str_grid_customer_ticket_flow($_SESSION["attr_tracking_id"]);
      }
      if (isset($attr_ticket_rating) && isset($NM_contr_var_session) && $NM_contr_var_session == "Yes") 
      {
          $_SESSION['attr_ticket_rating'] = $attr_ticket_rating;
          nm_limpa_str_grid_customer_ticket_flow($_SESSION["attr_ticket_rating"]);
      }
      $dir_raiz          = strrpos($_SERVER['PHP_SELF'],"/") ;  
      $dir_raiz          = substr($_SERVER['PHP_SELF'], 0, $dir_raiz + 1) ;  
      $this->New_Format  = true;
      $this->Xml_tag_label = false;
      $this->Tem_xml_res = false;
      $this->Xml_password = "";
      if (isset($_REQUEST['nm_xml_tag']) && !empty($_REQUEST['nm_xml_tag']))
      {
          $this->New_Format = ($_REQUEST['nm_xml_tag'] == "tag") ? true : false;
      }
      if (isset($_REQUEST['nm_xml_label']) && !empty($_REQUEST['nm_xml_label']))
      {
          $this->Xml_tag_label = ($_REQUEST['nm_xml_label'] == "S") ? true : false;
      }
      $this->Tem_xml_res  = true;
      if (isset($_REQUEST['SC_module_export']) && $_REQUEST['SC_module_export'] != "")
      { 
          $this->Tem_xml_res = (strpos(" " . $_REQUEST['SC_module_export'], "resume") !== false) ? true : false;
      } 
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['SC_Ind_Groupby'] == "sc_free_group_by" && empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['SC_Gb_Free_cmp']))
      {
          $this->Tem_xml_res  = false;
      }
      if (!is_file($this->Ini->root . $this->Ini->path_link . "grid_customer_ticket_flow/grid_customer_ticket_flow_res_xml.class.php"))
      {
          $this->Tem_xml_res  = false;
      }
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['embutida'] && isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_label']))
      {
          $this->Xml_tag_label = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_label'];
          $this->New_Format    = true;
      }
      $this->nm_location = $this->Ini->sc_protocolo . $this->Ini->server . $dir_raiz . "grid_customer_ticket_flow.php"; 
      require_once($this->Ini->path_aplicacao . "grid_customer_ticket_flow_total.class.php"); 
      $this->Tot      = new grid_customer_ticket_flow_total($this->Ini->sc_page);
      $this->prep_modulos("Tot");
      $Gb_geral = "quebra_geral_" . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['SC_Ind_Groupby'];
      if (method_exists($this->Tot,$Gb_geral))
      {
          $this->Tot->$Gb_geral();
          $this->count_ger = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['tot_geral'][1];
      }
      if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['embutida'] && !$this->Ini->sc_export_ajax) {
          require_once($this->Ini->path_lib_php . "/sc_progress_bar.php");
          $this->pb = new scProgressBar();
          $this->pb->setRoot($this->Ini->root);
          $this->pb->setDir($_SESSION['scriptcase']['grid_customer_ticket_flow']['glo_nm_path_imag_temp'] . "/");
          $this->pb->setProgressbarMd5($_GET['pbmd5']);
          $this->pb->initialize();
          $this->pb->setReturnUrl("grid_customer_ticket_flow.php");
          $this->pb->setReturnOption($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_return']);
          if ($this->Tem_xml_res) {
              $PB_plus = intval ($this->count_ger * 0.04);
              $PB_plus = ($PB_plus < 2) ? 2 : $PB_plus;
          }
          else {
              $PB_plus = intval ($this->count_ger * 0.02);
              $PB_plus = ($PB_plus < 1) ? 1 : $PB_plus;
          }
          $PB_tot = $this->count_ger + $PB_plus;
          $this->PB_dif = $PB_tot - $this->count_ger;
          $this->pb->setTotalSteps($PB_tot);
      }
      $this->nm_data    = new nm_data("en_us");
      $this->Arquivo      = "sc_xml";
      $this->Arquivo     .= "_" . date("YmdHis") . "_" . rand(0, 1000);
      $this->Arq_zip      = $this->Arquivo . "_grid_customer_ticket_flow.zip";
      $this->Arquivo     .= "_grid_customer_ticket_flow";
      $this->Arquivo_view = $this->Arquivo . "_view.xml";
      $this->Arquivo     .= ".xml";
      $this->Tit_doc      = "grid_customer_ticket_flow.xml";
      $this->Tit_zip      = "grid_customer_ticket_flow.zip";
      $this->Grava_view   = false;
      if (strtolower($_SESSION['scriptcase']['charset']) != strtolower($_SESSION['scriptcase']['charset_html']))
      {
          $this->Grava_view = true;
      }
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
   function grava_arquivo()
   {
      global $nm_lang;
      global $nm_nada, $nm_lang;

      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->sc_proc_grid = false; 
      $nm_raiz_img  = ""; 
      $this->sc_where_orig   = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['where_orig'];
      $this->sc_where_atual  = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['where_pesq'];
      $this->sc_where_filtro = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['where_pesq_filtro'];
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['campos_busca']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['campos_busca']))
      { 
          $Busca_temp = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['campos_busca'];
          if ($_SESSION['scriptcase']['charset'] != "UTF-8")
          {
              $Busca_temp = NM_conv_charset($Busca_temp, $_SESSION['scriptcase']['charset'], "UTF-8");
          }
          $this->ticketdate = (isset($Busca_temp['ticketdate'])) ? $Busca_temp['ticketdate'] : ""; 
          $tmp_pos = (is_string($this->ticketdate)) ? strpos($this->ticketdate, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->ticketdate))
          {
              $this->ticketdate = substr($this->ticketdate, 0, $tmp_pos);
          }
          $this->ticketcontent = (isset($Busca_temp['ticketcontent'])) ? $Busca_temp['ticketcontent'] : ""; 
          $tmp_pos = (is_string($this->ticketcontent)) ? strpos($this->ticketcontent, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->ticketcontent))
          {
              $this->ticketcontent = substr($this->ticketcontent, 0, $tmp_pos);
          }
          $this->ticketfile1 = (isset($Busca_temp['ticketfile1'])) ? $Busca_temp['ticketfile1'] : ""; 
          $tmp_pos = (is_string($this->ticketfile1)) ? strpos($this->ticketfile1, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->ticketfile1))
          {
              $this->ticketfile1 = substr($this->ticketfile1, 0, $tmp_pos);
          }
          $this->ticketfile2 = (isset($Busca_temp['ticketfile2'])) ? $Busca_temp['ticketfile2'] : ""; 
          $tmp_pos = (is_string($this->ticketfile2)) ? strpos($this->ticketfile2, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->ticketfile2))
          {
              $this->ticketfile2 = substr($this->ticketfile2, 0, $tmp_pos);
          }
          $this->ticketfile3 = (isset($Busca_temp['ticketfile3'])) ? $Busca_temp['ticketfile3'] : ""; 
          $tmp_pos = (is_string($this->ticketfile3)) ? strpos($this->ticketfile3, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->ticketfile3))
          {
              $this->ticketfile3 = substr($this->ticketfile3, 0, $tmp_pos);
          }
          $this->operatorid = (isset($Busca_temp['operatorid'])) ? $Busca_temp['operatorid'] : ""; 
          $tmp_pos = (is_string($this->operatorid)) ? strpos($this->operatorid, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->operatorid))
          {
              $this->operatorid = substr($this->operatorid, 0, $tmp_pos);
          }
          $this->messagetype = (isset($Busca_temp['messagetype'])) ? $Busca_temp['messagetype'] : ""; 
          $tmp_pos = (is_string($this->messagetype)) ? strpos($this->messagetype, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->messagetype))
          {
              $this->messagetype = substr($this->messagetype, 0, $tmp_pos);
          }
      } 
      $this->nm_where_dinamico = "";
      $_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  $this->Ini->Nm_lang['lang_btns_inst'] = $this->Ini->Nm_lang['lang_button_send_message'];
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off'; 
      if  (!empty($this->nm_where_dinamico)) 
      {   
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['where_pesq'] .= $this->nm_where_dinamico;
      }   
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_name']))
      {
          $Pos = strrpos($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_name'], ".");
          if ($Pos === false) {
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_name'] .= ".xml";
          }
          $this->Arquivo = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_name'];
          $this->Arq_zip = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_name'];
          $this->Tit_doc = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_name'];
          $Pos = strrpos($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_name'], ".");
          if ($Pos !== false) {
              $this->Arq_zip = substr($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_name'], 0, $Pos);
          }
          $this->Arq_zip .= ".zip";
          $this->Tit_zip  = $this->Arq_zip;
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_name']);
      }
      if (!$this->Grava_view)
      {
          $this->Arquivo_view = $this->Arquivo;
      }
      $this->arr_export = array('label' => array(), 'lines' => array());
      $this->arr_span   = array();

      if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['embutida'])
      { 
          $xml_charset = $_SESSION['scriptcase']['charset'];
          $this->Xml_f = $this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arquivo;
          $this->Zip_f = $this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arq_zip;
          $xml_f = fopen($this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arquivo, "w");
          fwrite($xml_f, "<?xml version=\"1.0\" encoding=\"$xml_charset\" ?>\r\n");
          fwrite($xml_f, "<root>\r\n");
          if ($this->Grava_view)
          {
              $xml_charset_v = $_SESSION['scriptcase']['charset_html'];
              $xml_v         = fopen($this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arquivo_view, "w");
              fwrite($xml_v, "<?xml version=\"1.0\" encoding=\"$xml_charset_v\" ?>\r\n");
              fwrite($xml_v, "<root>\r\n");
          }
      }
      $this->nm_field_dinamico = array();
      $this->nm_order_dinamico = array();
      $nmgp_select_count = "SELECT count(*) AS countTest from " . $this->Ini->nm_tabela; 
      if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_sybase))
      { 
          $nmgp_select = "SELECT str_replace (convert(char(10),ticketdate,102), '.', '-') + ' ' + convert(char(8),ticketdate,20), statusid as messagestatus, operatorid, ticketcontent, ticketfile1, ticketfile2, ticketfile3, ticketfilename1, ticketfilename2, ticketfilename3, ticketid from " . $this->Ini->nm_tabela; 
      } 
      elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_mysql))
      { 
          $nmgp_select = "SELECT ticketdate, statusid as messagestatus, operatorid, ticketcontent, ticketfile1, ticketfile2, ticketfile3, ticketfilename1, ticketfilename2, ticketfilename3, ticketid from " . $this->Ini->nm_tabela; 
      } 
      elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_mssql))
      { 
       $nmgp_select = "SELECT convert(char(23),ticketdate,121), statusid as messagestatus, operatorid, ticketcontent, ticketfile1, ticketfile2, ticketfile3, ticketfilename1, ticketfilename2, ticketfilename3, ticketid from " . $this->Ini->nm_tabela; 
      } 
      elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_oracle))
      { 
          $nmgp_select = "SELECT ticketdate, statusid as messagestatus, operatorid, ticketcontent, ticketfile1, ticketfile2, ticketfile3, ticketfilename1, ticketfilename2, ticketfilename3, ticketid from " . $this->Ini->nm_tabela; 
      } 
      elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_informix))
      { 
          $nmgp_select = "SELECT EXTEND(ticketdate, YEAR TO FRACTION), statusid as messagestatus, operatorid, ticketcontent, LOTOFILE(ticketfile1, '" . $this->Ini->root . $this->Ini->path_imag_temp . "/sc_blob_informix', 'client') as ticketfile1, LOTOFILE(ticketfile2, '" . $this->Ini->root . $this->Ini->path_imag_temp . "/sc_blob_informix', 'client') as ticketfile2, LOTOFILE(ticketfile3, '" . $this->Ini->root . $this->Ini->path_imag_temp . "/sc_blob_informix', 'client') as ticketfile3, ticketfilename1, ticketfilename2, ticketfilename3, ticketid from " . $this->Ini->nm_tabela; 
      } 
      else 
      { 
          $nmgp_select = "SELECT ticketdate, statusid as messagestatus, operatorid, ticketcontent, ticketfile1, ticketfile2, ticketfile3, ticketfilename1, ticketfilename2, ticketfilename3, ticketid from " . $this->Ini->nm_tabela; 
      } 
      $nmgp_select .= " " . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['where_pesq'];
      $nmgp_select_count .= " " . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['where_pesq'];
      $nmgp_order_by = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['order_grid'];
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
      if (!$rs->EOF)
      {
         $this->ticketdate = $rs->fields[0] ;  
         $this->messagestatus = $rs->fields[1] ;  
         $this->operatorid = $rs->fields[2] ;  
         $this->operatorid = (string)$this->operatorid;
         $this->ticketcontent = $rs->fields[3] ;  
          if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_informix))
          { 
              $this->ticketfile1 = "";  
              if (is_file($rs_grid->fields[4])) 
              { 
                  $this->ticketfile1 = file_get_contents($rs_grid->fields[4]);  
              } 
          } 
         elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_ibase))
         { 
             $this->ticketfile1 = $this->Db->BlobDecode($rs->fields[4]) ;  
         } 
         elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_oracle))
         { 
             $this->ticketfile1 = $this->Db->BlobDecode($rs->fields[4]) ;  
         } 
         else
         { 
             $this->ticketfile1 = $rs->fields[4] ;  
         } 
          if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_informix))
          { 
              $this->ticketfile2 = "";  
              if (is_file($rs_grid->fields[5])) 
              { 
                  $this->ticketfile2 = file_get_contents($rs_grid->fields[5]);  
              } 
          } 
         elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_ibase))
         { 
             $this->ticketfile2 = $this->Db->BlobDecode($rs->fields[5]) ;  
         } 
         elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_oracle))
         { 
             $this->ticketfile2 = $this->Db->BlobDecode($rs->fields[5]) ;  
         } 
         else
         { 
             $this->ticketfile2 = $rs->fields[5] ;  
         } 
          if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_informix))
          { 
              $this->ticketfile3 = "";  
              if (is_file($rs_grid->fields[6])) 
              { 
                  $this->ticketfile3 = file_get_contents($rs_grid->fields[6]);  
              } 
          } 
         elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_ibase))
         { 
             $this->ticketfile3 = $this->Db->BlobDecode($rs->fields[6]) ;  
         } 
         elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_oracle))
         { 
             $this->ticketfile3 = $this->Db->BlobDecode($rs->fields[6]) ;  
         } 
         else
         { 
             $this->ticketfile3 = $rs->fields[6] ;  
         } 
         $this->ticketfilename1 = $rs->fields[7] ;  
         $this->ticketfilename2 = $rs->fields[8] ;  
         $this->ticketfilename3 = $rs->fields[9] ;  
         $this->ticketid = $rs->fields[10] ;  
         $this->ticketid = (string)$this->ticketid;
   $_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
if (!isset($_SESSION['attr_ticket_rating'])) {$_SESSION['attr_ticket_rating'] = "";}
if (!isset($this->sc_temp_attr_ticket_rating)) {$this->sc_temp_attr_ticket_rating = (isset($_SESSION['attr_ticket_rating'])) ? $_SESSION['attr_ticket_rating'] : "";}
if (!isset($_SESSION['attr_tracking_id'])) {$_SESSION['attr_tracking_id'] = "";}
if (!isset($this->sc_temp_attr_tracking_id)) {$this->sc_temp_attr_tracking_id = (isset($_SESSION['attr_tracking_id'])) ? $_SESSION['attr_tracking_id'] : "";}
if (!isset($_SESSION['attr_ticket_status'])) {$_SESSION['attr_ticket_status'] = "";}
if (!isset($this->sc_temp_attr_ticket_status)) {$this->sc_temp_attr_ticket_status = (isset($_SESSION['attr_ticket_status'])) ? $_SESSION['attr_ticket_status'] : "";}
if (!isset($_SESSION['v_ticketid'])) {$_SESSION['v_ticketid'] = "";}
if (!isset($this->sc_temp_v_ticketid)) {$this->sc_temp_v_ticketid = (isset($_SESSION['v_ticketid'])) ? $_SESSION['v_ticketid'] : "";}
  $st = $this->sc_temp_v_ticketid;
$st = $this->GetTicketStatus($st);
$this->sc_temp_attr_ticket_status = $st;

$trk = $this->GetTrackingId($this->sc_temp_v_ticketid);
$this->sc_temp_attr_tracking_id = $trk;

$this->nmgp_botoes["btn_Rating"] = 'off';;
$this->nmgp_botoes["insert"] = 'on';;


if($st == 'CLOSED' or $st == 'SOLVED')
{
	$this->nmgp_botoes["insert"] = 'off';;
	$is_rated = $this->GetTicketRating($this->sc_temp_v_ticketid);
	$this->sc_temp_attr_ticket_rating = $is_rated;

	if($is_rated <= 0)
	{
	     $this->nmgp_botoes["btn_Rating"] = 'on';;
	}
}
if (isset($this->sc_temp_v_ticketid)) {$_SESSION['v_ticketid'] = $this->sc_temp_v_ticketid;}
if (isset($this->sc_temp_attr_ticket_status)) {$_SESSION['attr_ticket_status'] = $this->sc_temp_attr_ticket_status;}
if (isset($this->sc_temp_attr_tracking_id)) {$_SESSION['attr_tracking_id'] = $this->sc_temp_attr_tracking_id;}
if (isset($this->sc_temp_attr_ticket_rating)) {$_SESSION['attr_ticket_rating'] = $this->sc_temp_attr_ticket_rating;}
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
      }
      $this->SC_seq_register = 0;
      $this->xml_registro = "";
      $PB_tot = (isset($this->count_ger) && $this->count_ger > 0) ? "/" . $this->count_ger : "";
      while (!$rs->EOF)
      {
         $this->SC_seq_register++;
         if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['embutida'] && !$this->Ini->sc_export_ajax) {
             $Mens_bar = NM_charset_to_utf8($this->Ini->Nm_lang['lang_othr_prcs']);
             $this->pb->setProgressbarMessage($Mens_bar . ": " . $this->SC_seq_register . $PB_tot);
             $this->pb->addSteps(1);
         }
         if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['embutida'])
         { 
             $this->xml_registro .= "<" . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['embutida_tit'] . ">\r\n";
         }
         elseif ($this->New_Format)
         {
             $this->xml_registro = "<grid_customer_ticket_flow>\r\n";
         }
         else
         {
             $this->xml_registro = "<grid_customer_ticket_flow";
         }
         $this->ticketdate = $rs->fields[0] ;  
         $this->messagestatus = $rs->fields[1] ;  
         $this->operatorid = $rs->fields[2] ;  
         $this->operatorid = (string)$this->operatorid;
         $this->ticketcontent = $rs->fields[3] ;  
          if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_informix))
          { 
              $this->ticketfile1 = "";  
              if (is_file($rs_grid->fields[4])) 
              { 
                  $this->ticketfile1 = file_get_contents($rs_grid->fields[4]);  
              } 
          } 
         elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_ibase))
         { 
             $this->ticketfile1 = $this->Db->BlobDecode($rs->fields[4]) ;  
         } 
         elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_oracle))
         { 
             $this->ticketfile1 = $this->Db->BlobDecode($rs->fields[4]) ;  
         } 
         else
         { 
             $this->ticketfile1 = $rs->fields[4] ;  
         } 
          if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_informix))
          { 
              $this->ticketfile2 = "";  
              if (is_file($rs_grid->fields[5])) 
              { 
                  $this->ticketfile2 = file_get_contents($rs_grid->fields[5]);  
              } 
          } 
         elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_ibase))
         { 
             $this->ticketfile2 = $this->Db->BlobDecode($rs->fields[5]) ;  
         } 
         elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_oracle))
         { 
             $this->ticketfile2 = $this->Db->BlobDecode($rs->fields[5]) ;  
         } 
         else
         { 
             $this->ticketfile2 = $rs->fields[5] ;  
         } 
          if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_informix))
          { 
              $this->ticketfile3 = "";  
              if (is_file($rs_grid->fields[6])) 
              { 
                  $this->ticketfile3 = file_get_contents($rs_grid->fields[6]);  
              } 
          } 
         elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_ibase))
         { 
             $this->ticketfile3 = $this->Db->BlobDecode($rs->fields[6]) ;  
         } 
         elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_oracle))
         { 
             $this->ticketfile3 = $this->Db->BlobDecode($rs->fields[6]) ;  
         } 
         else
         { 
             $this->ticketfile3 = $rs->fields[6] ;  
         } 
         $this->ticketfilename1 = $rs->fields[7] ;  
         $this->ticketfilename2 = $rs->fields[8] ;  
         $this->ticketfilename3 = $rs->fields[9] ;  
         $this->ticketid = $rs->fields[10] ;  
         $this->ticketid = (string)$this->ticketid;
         //----- lookup - messagestatus
         $this->look_messagestatus = $this->messagestatus; 
         $this->Lookup->lookup_messagestatus($this->look_messagestatus, $this->messagestatus) ; 
         $this->look_messagestatus = ($this->look_messagestatus == "&nbsp;") ? "" : $this->look_messagestatus; 
         //----- lookup - operatorid
         $this->look_operatorid = $this->operatorid; 
         $this->Lookup->lookup_operatorid($this->look_operatorid, $this->operatorid) ; 
         $this->look_operatorid = ($this->look_operatorid == "&nbsp;") ? "" : $this->look_operatorid; 
         $this->ticketfile1 = $this->ticketfilename1;
         $this->ticketfile2 = $this->ticketfilename2;
         $this->ticketfile3 = $this->ticketfilename3;
         $this->sc_proc_grid = true; 
         foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['field_order'] as $Cada_col)
         { 
            if (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off")
            { 
                $NM_func_exp = "NM_export_" . $Cada_col;
                $this->$NM_func_exp();
            } 
         } 
         if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['embutida'])
         { 
             $this->xml_registro .= "</" . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['embutida_tit'] . ">\r\n";
         }
         elseif ($this->New_Format)
         {
             $this->xml_registro .= "</grid_customer_ticket_flow>\r\n";
         }
         else
         {
             $this->xml_registro .= " />\r\n";
         }
         if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['embutida'])
         { 
             fwrite($xml_f, $this->xml_registro);
             if ($this->Grava_view)
             {
                fwrite($xml_v, $this->xml_registro);
             }
         }
         $rs->MoveNext();
      }
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['embutida'])
      { 
          if (!$this->New_Format)
          {
              $this->xml_registro = "";
          }
          $_SESSION['scriptcase']['export_return'] = $this->xml_registro;
      }
      else
      { 
          fwrite($xml_f, "</root>");
          fclose($xml_f);
          if ($this->Grava_view)
          {
             fwrite($xml_v, "</root>");
             fclose($xml_v);
          }
          if ($this->Tem_xml_res)
          { 
              if (!$this->Ini->sc_export_ajax) {
                  $this->PB_dif = intval ($this->PB_dif / 2);
                  $Mens_bar  = NM_charset_to_utf8($this->Ini->Nm_lang['lang_othr_prcs']);
                  $Mens_smry = NM_charset_to_utf8($this->Ini->Nm_lang['lang_othr_smry_titl']);
                  $this->pb->setProgressbarMessage($Mens_bar . ": " . $Mens_smry);
                  $this->pb->addSteps($this->PB_dif);
              }
              require_once($this->Ini->path_aplicacao . "grid_customer_ticket_flow_res_xml.class.php");
              $this->Res = new grid_customer_ticket_flow_res_xml();
              $this->prep_modulos("Res");
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_res_grid'] = true;
              $this->Res->monta_xml();
          } 
          if (!$this->Ini->sc_export_ajax) {
              $Mens_bar = NM_charset_to_utf8($this->Ini->Nm_lang['lang_btns_export_finished']);
              $this->pb->setProgressbarMessage($Mens_bar);
              $this->pb->addSteps($this->PB_dif);
          }
          if ($this->Xml_password != "" || $this->Tem_xml_res)
          { 
              $str_zip    = "";
              $Parm_pass  = ($this->Xml_password != "") ? " -p" : "";
              $Zip_f      = (FALSE !== strpos($this->Zip_f, ' ')) ? " \"" . $this->Zip_f . "\"" :  $this->Zip_f;
              $Arq_input  = (FALSE !== strpos($this->Xml_f, ' ')) ? " \"" . $this->Xml_f . "\"" :  $this->Xml_f;
              if (is_file($Zip_f)) {
                  unlink($Zip_f);
              }
              if (FALSE !== strpos(strtolower(php_uname()), 'windows')) 
              {
                  chdir($this->Ini->path_third . "/zip/windows");
                  $str_zip = "zip.exe " . strtoupper($Parm_pass) . " -j " . $this->Xml_password . " " . $Zip_f . " " . $Arq_input;
              }
              elseif (FALSE !== strpos(strtolower(php_uname()), 'linux')) 
              {
                  if (FALSE !== strpos(strtolower(php_uname()), 'i686')) 
                  {
                      chdir($this->Ini->path_third . "/zip/linux-i386/bin");
                  }
                  else
                  {
                      chdir($this->Ini->path_third . "/zip/linux-amd64/bin");
                  }
                  $str_zip = "./7za " . $Parm_pass . $this->Xml_password . " a " . $Zip_f . " " . $Arq_input;
              }
              elseif (FALSE !== strpos(strtolower(php_uname()), 'darwin'))
              {
                  chdir($this->Ini->path_third . "/zip/mac/bin");
                  $str_zip = "./7za " . $Parm_pass . $this->Xml_password . " a " . $Zip_f . " " . $Arq_input;
              }
              if (!empty($str_zip)) {
                  exec($str_zip);
              }
              // ----- ZIP log
              $fp = @fopen(trim(str_replace(array(".zip",'"'), array(".log",""), $Zip_f)), 'w');
              if ($fp)
              {
                  @fwrite($fp, $str_zip . "\r\n\r\n");
                  @fclose($fp);
              }
              unlink($Arq_input);
              $this->Arquivo = $this->Arq_zip;
              $this->Xml_f   = $this->Zip_f;
              $this->Tit_doc = $this->Tit_zip;
              if ($this->Tem_xml_res)
              { 
                  $str_zip   = "";
                  $Arq_res   = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_res_file']['xml'];
                  $Arq_input = (FALSE !== strpos($Arq_res, ' ')) ? " \"" . $Arq_res . "\"" :  $Arq_res;
                  if (FALSE !== strpos(strtolower(php_uname()), 'windows')) 
                  {
                      $str_zip = "zip.exe " . strtoupper($Parm_pass) . " -j -u " . $this->Xml_password . " " . $Zip_f . " " . $Arq_input;
                  }
                  elseif (FALSE !== strpos(strtolower(php_uname()), 'linux')) 
                  {
                      $str_zip = "./7za " . $Parm_pass . $this->Xml_password . " a " . $Zip_f . " " . $Arq_input;
                  }
                  elseif (FALSE !== strpos(strtolower(php_uname()), 'darwin'))
                  {
                      $str_zip = "./7za " . $Parm_pass . $this->Xml_password . " a " . $Zip_f . " " . $Arq_input;
                  }
                  if (!empty($str_zip)) {
                      exec($str_zip);
                  }
                  // ----- ZIP log
                  $fp = @fopen(trim(str_replace(array(".zip",'"'), array(".log",""), $Zip_f)), 'a');
                  if ($fp)
                  {
                      @fwrite($fp, $str_zip . "\r\n\r\n");
                      @fclose($fp);
                  }
                  unlink($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_res_file']['xml']);
              }
              if ($this->Grava_view)
              {
                  $str_zip    = "";
                  $xml_view_f = $this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arquivo_view;
                  $zip_view_f = str_replace(".zip", "_view.zip", $this->Zip_f);
                  $zip_arq_v  = str_replace(".zip", "_view.zip", $this->Arq_zip);
                  $Zip_f      = (FALSE !== strpos($zip_view_f, ' ')) ? " \"" . $zip_view_f . "\"" :  $zip_view_f;
                  $Arq_input  = (FALSE !== strpos($xml_view_ff, ' ')) ? " \"" . $xml_view_f . "\"" :  $xml_view_f;
                  if (is_file($Zip_f)) {
                      unlink($Zip_f);
                  }
                  if (FALSE !== strpos(strtolower(php_uname()), 'windows')) 
                  {
                      chdir($this->Ini->path_third . "/zip/windows");
                      $str_zip = "zip.exe " . strtoupper($Parm_pass) . " -j " . $this->Xml_password . " " . $Zip_f . " " . $Arq_input;
                  }
                  elseif (FALSE !== strpos(strtolower(php_uname()), 'linux')) 
                  {
                      if (FALSE !== strpos(strtolower(php_uname()), 'i686')) 
                      {
                          chdir($this->Ini->path_third . "/zip/linux-i386/bin");
                      }
                      else
                      {
                          chdir($this->Ini->path_third . "/zip/linux-amd64/bin");
                      }
                      $str_zip = "./7za " . $Parm_pass . $this->Xml_password . " a " . $Zip_f . " " . $Arq_input;
                  }
                  elseif (FALSE !== strpos(strtolower(php_uname()), 'darwin'))
                  {
                      chdir($this->Ini->path_third . "/zip/mac/bin");
                      $str_zip = "./7za " . $Parm_pass . $this->Xml_password . " a " . $Zip_f . " " . $Arq_input;
                  }
                  if (!empty($str_zip)) {
                      exec($str_zip);
                  }
                  // ----- ZIP log
                  $fp = @fopen(trim(str_replace(array(".zip",'"'), array(".log",""), $Zip_f)), 'a');
                  if ($fp)
                  {
                      @fwrite($fp, $str_zip . "\r\n\r\n");
                      @fclose($fp);
                  }
                  unlink($Arq_input);
                  $this->Arquivo_view = $zip_arq_v;
                  if ($this->Tem_xml_res)
                  { 
                      $str_zip   = "";
                      $Arq_res   = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_res_file']['view'];
                      $Arq_input = (FALSE !== strpos($Arq_res, ' ')) ? " \"" . $Arq_res . "\"" :  $Arq_res;
                      if (FALSE !== strpos(strtolower(php_uname()), 'windows')) 
                      {
                          $str_zip = "zip.exe " . strtoupper($Parm_pass) . " -j -u " . $this->Xml_password . " " . $Zip_f . " " . $Arq_input;
                      }
                      elseif (FALSE !== strpos(strtolower(php_uname()), 'linux')) 
                      {
                          $str_zip = "./7za " . $Parm_pass . $this->Xml_password . " a " . $Zip_f . " " . $Arq_input;
                      }
                      elseif (FALSE !== strpos(strtolower(php_uname()), 'darwin'))
                      {
                          $str_zip = "./7za " . $Parm_pass . $this->Xml_password . " a " . $Zip_f . " " . $Arq_input;
                      }
                      if (!empty($str_zip)) {
                          exec($str_zip);
                      }
                      // ----- ZIP log
                      $fp = @fopen(trim(str_replace(array(".zip",'"'), array(".log",""), $Zip_f)), 'a');
                      if ($fp)
                      {
                          @fwrite($fp, $str_zip . "\r\n\r\n");
                          @fclose($fp);
                      }
                      unlink($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_res_file']['view']);
                  }
              } 
              else 
              {
                  $this->Arquivo_view = $this->Arq_zip;
              } 
              unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_res_grid']);
          } 
      }
      if(isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['export_sel_columns']['field_order']))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['field_order'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['export_sel_columns']['field_order'];
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['export_sel_columns']['field_order']);
      }
      if(isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['export_sel_columns']['usr_cmp_sel']))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['usr_cmp_sel'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['export_sel_columns']['usr_cmp_sel'];
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['export_sel_columns']['usr_cmp_sel']);
      }
      $rs->Close();
   }
   //----- ticketdate
   function NM_export_ticketdate()
   {
             if (substr($this->ticketdate, 10, 1) == "-") 
             { 
                 $this->ticketdate = substr($this->ticketdate, 0, 10) . " " . substr($this->ticketdate, 11);
             } 
             if (substr($this->ticketdate, 13, 1) == ".") 
             { 
                $this->ticketdate = substr($this->ticketdate, 0, 13) . ":" . substr($this->ticketdate, 14, 2) . ":" . substr($this->ticketdate, 17);
             } 
             $conteudo_x =  $this->ticketdate;
             nm_conv_limpa_dado($conteudo_x, "YYYY-MM-DD HH:II:SS");
             if (is_numeric($conteudo_x) && strlen($conteudo_x) > 0) 
             { 
                 $this->nm_data->SetaData($this->ticketdate, "YYYY-MM-DD HH:II:SS  ");
                 $this->ticketdate = $this->nm_data->FormataSaida($this->nm_data->FormatRegion("DH", "ddmmaaaa;hhiiss"));
             } 
         if ($this->Xml_tag_label)
         {
             $SC_Label = (isset($this->New_label['ticketdate'])) ? $this->New_label['ticketdate'] : "" . $this->Ini->Nm_lang['lang_ticketmessage_fild_ticketdate'] . ""; 
         }
         else
         {
             $SC_Label = "ticketdate"; 
         }
         $this->clear_tag($SC_Label); 
         if ($this->New_Format)
         {
             $this->xml_registro .= " <" . $SC_Label . ">" . $this->trata_dados($this->ticketdate) . "</" . $SC_Label . ">\r\n";
         }
         else
         {
             $this->xml_registro .= " " . $SC_Label . " =\"" . $this->trata_dados($this->ticketdate) . "\"";
         }
   }
   //----- messagestatus
   function NM_export_messagestatus()
   {
         if ($_SESSION['scriptcase']['charset'] == "UTF-8" && !NM_is_utf8($this->look_messagestatus))
         {
             $this->look_messagestatus = sc_convert_encoding($this->look_messagestatus, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         if ($this->Xml_tag_label)
         {
             $SC_Label = (isset($this->New_label['messagestatus'])) ? $this->New_label['messagestatus'] : "" . $this->Ini->Nm_lang['lang_ticketmessage_fild_statusid'] . ""; 
         }
         else
         {
             $SC_Label = "messagestatus"; 
         }
         $this->clear_tag($SC_Label); 
         if ($this->New_Format)
         {
             $this->xml_registro .= " <" . $SC_Label . ">" . $this->trata_dados($this->look_messagestatus) . "</" . $SC_Label . ">\r\n";
         }
         else
         {
             $this->xml_registro .= " " . $SC_Label . " =\"" . $this->trata_dados($this->look_messagestatus) . "\"";
         }
   }
   //----- operatorid
   function NM_export_operatorid()
   {
         nmgp_Form_Num_Val($this->look_operatorid, $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "2", "", "N:" . $_SESSION['scriptcase']['reg_conf']['neg_num'] , $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['num_group_digit']) ; 
         if ($_SESSION['scriptcase']['charset'] == "UTF-8" && !NM_is_utf8($this->look_operatorid))
         {
             $this->look_operatorid = sc_convert_encoding($this->look_operatorid, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         if ($this->Xml_tag_label)
         {
             $SC_Label = (isset($this->New_label['operatorid'])) ? $this->New_label['operatorid'] : "" . $this->Ini->Nm_lang['lang_ticketmessage_fild_operatorid'] . ""; 
         }
         else
         {
             $SC_Label = "operatorid"; 
         }
         $this->clear_tag($SC_Label); 
         if ($this->New_Format)
         {
             $this->xml_registro .= " <" . $SC_Label . ">" . $this->trata_dados($this->look_operatorid) . "</" . $SC_Label . ">\r\n";
         }
         else
         {
             $this->xml_registro .= " " . $SC_Label . " =\"" . $this->trata_dados($this->look_operatorid) . "\"";
         }
   }
   //----- ticketcontent
   function NM_export_ticketcontent()
   {
         if ($_SESSION['scriptcase']['charset'] == "UTF-8" && !NM_is_utf8($this->ticketcontent))
         {
             $this->ticketcontent = sc_convert_encoding($this->ticketcontent, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         if ($this->Xml_tag_label)
         {
             $SC_Label = (isset($this->New_label['ticketcontent'])) ? $this->New_label['ticketcontent'] : "" . $this->Ini->Nm_lang['lang_ticketmessage_fild_ticketcontent'] . ""; 
         }
         else
         {
             $SC_Label = "ticketcontent"; 
         }
         $this->clear_tag($SC_Label); 
         if ($this->New_Format)
         {
             $this->xml_registro .= " <" . $SC_Label . ">" . $this->trata_dados($this->ticketcontent) . "</" . $SC_Label . ">\r\n";
         }
         else
         {
             $this->xml_registro .= " " . $SC_Label . " =\"" . $this->trata_dados($this->ticketcontent) . "\"";
         }
   }
   //----- ticketfile1
   function NM_export_ticketfile1()
   {
         if ($_SESSION['scriptcase']['charset'] == "UTF-8" && !NM_is_utf8($this->ticketfile1))
         {
             $this->ticketfile1 = sc_convert_encoding($this->ticketfile1, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         if ($this->Xml_tag_label)
         {
             $SC_Label = (isset($this->New_label['ticketfile1'])) ? $this->New_label['ticketfile1'] : "" . $this->Ini->Nm_lang['lang_ticketmessage_fild_ticketfile1'] . ""; 
         }
         else
         {
             $SC_Label = "ticketfile1"; 
         }
         $this->clear_tag($SC_Label); 
         if ($this->New_Format)
         {
             $this->xml_registro .= " <" . $SC_Label . ">" . $this->trata_dados($this->ticketfile1) . "</" . $SC_Label . ">\r\n";
         }
         else
         {
             $this->xml_registro .= " " . $SC_Label . " =\"" . $this->trata_dados($this->ticketfile1) . "\"";
         }
   }
   //----- ticketfile2
   function NM_export_ticketfile2()
   {
         if ($_SESSION['scriptcase']['charset'] == "UTF-8" && !NM_is_utf8($this->ticketfile2))
         {
             $this->ticketfile2 = sc_convert_encoding($this->ticketfile2, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         if ($this->Xml_tag_label)
         {
             $SC_Label = (isset($this->New_label['ticketfile2'])) ? $this->New_label['ticketfile2'] : "" . $this->Ini->Nm_lang['lang_ticketmessage_fild_ticketfile2'] . ""; 
         }
         else
         {
             $SC_Label = "ticketfile2"; 
         }
         $this->clear_tag($SC_Label); 
         if ($this->New_Format)
         {
             $this->xml_registro .= " <" . $SC_Label . ">" . $this->trata_dados($this->ticketfile2) . "</" . $SC_Label . ">\r\n";
         }
         else
         {
             $this->xml_registro .= " " . $SC_Label . " =\"" . $this->trata_dados($this->ticketfile2) . "\"";
         }
   }
   //----- ticketfile3
   function NM_export_ticketfile3()
   {
         if ($_SESSION['scriptcase']['charset'] == "UTF-8" && !NM_is_utf8($this->ticketfile3))
         {
             $this->ticketfile3 = sc_convert_encoding($this->ticketfile3, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         if ($this->Xml_tag_label)
         {
             $SC_Label = (isset($this->New_label['ticketfile3'])) ? $this->New_label['ticketfile3'] : "" . $this->Ini->Nm_lang['lang_ticketmessage_fild_ticketfile3'] . ""; 
         }
         else
         {
             $SC_Label = "ticketfile3"; 
         }
         $this->clear_tag($SC_Label); 
         if ($this->New_Format)
         {
             $this->xml_registro .= " <" . $SC_Label . ">" . $this->trata_dados($this->ticketfile3) . "</" . $SC_Label . ">\r\n";
         }
         else
         {
             $this->xml_registro .= " " . $SC_Label . " =\"" . $this->trata_dados($this->ticketfile3) . "\"";
         }
   }

   //----- 
   function trata_dados($conteudo)
   {
      $str_temp =  $conteudo;
      $str_temp =  str_replace("<br />", "",  $str_temp);
      $str_temp =  str_replace("&", "&amp;",  $str_temp);
      $str_temp =  str_replace("<", "&lt;",   $str_temp);
      $str_temp =  str_replace(">", "&gt;",   $str_temp);
      $str_temp =  str_replace("'", "&apos;", $str_temp);
      $str_temp =  str_replace('"', "&quot;",  $str_temp);
      $str_temp =  str_replace('(', "_",  $str_temp);
      $str_temp =  str_replace(')', "",  $str_temp);
      return ($str_temp);
   }

   function clear_tag(&$conteudo)
   {
      $out = (is_numeric(substr($conteudo, 0, 1)) || substr($conteudo, 0, 1) == "") ? "_" : "";
      $str_temp = "abcdefghijklmnopqrstuvwxyz0123456789";
      for ($i = 0; $i < strlen($conteudo); $i++)
      {
          $char = substr($conteudo, $i, 1);
          $ok = false;
          for ($z = 0; $z < strlen($str_temp); $z++)
          {
              if (strtolower($char) == substr($str_temp, $z, 1))
              {
                  $ok = true;
                  break;
              }
          }
          $out .= ($ok) ? $char : "_";
      }
      $conteudo = $out;
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
      unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_file']);
      if (is_file($this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arquivo))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_file'] = $this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arquivo;
      }
      $path_doc_md5 = md5($this->Ini->path_imag_temp . "/" . $this->Arquivo);
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow'][$path_doc_md5][0] = $this->Ini->path_imag_temp . "/" . $this->Arquivo;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow'][$path_doc_md5][1] = $this->Tit_doc;
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
      unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_file']);
      if (is_file($this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arquivo))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_file'] = $this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arquivo;
      }
      $path_doc_md5 = md5($this->Ini->path_imag_temp . "/" . $this->Arquivo);
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow'][$path_doc_md5][0] = $this->Ini->path_imag_temp . "/" . $this->Arquivo;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow'][$path_doc_md5][1] = $this->Tit_doc;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML<?php echo $_SESSION['scriptcase']['reg_conf']['html_dir'] ?>>
<HEAD>
 <TITLE>Ticket: <?php echo $_SESSION['v_trackingid'] ?> :: XML</TITLE>
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
   <td class="scExportTitle" style="height: 25px">XML</td>
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
<form name="Fview" method="get" action="<?php echo $this->Ini->path_imag_temp . "/" . $this->Arquivo_view ?>" target="_blank" style="display: none"> 
</form>
<form name="Fdown" method="get" action="grid_customer_ticket_flow_download.php" target="_blank" style="display: none"> 
<input type="hidden" name="script_case_init" value="<?php echo NM_encode_input($this->Ini->sc_page); ?>"> 
<input type="hidden" name="nm_tit_doc" value="grid_customer_ticket_flow"> 
<input type="hidden" name="nm_name_doc" value="<?php echo $path_doc_md5 ?>"> 
</form>
<FORM name="F0" method=post action="grid_customer_ticket_flow.php" style="display: none"> 
<INPUT type="hidden" name="script_case_init" value="<?php echo NM_encode_input($this->Ini->sc_page); ?>"> 
<INPUT type="hidden" name="nmgp_opcao" value="<?php echo NM_encode_input($_SESSION['sc_session'][$this->Ini->sc_page]['grid_customer_ticket_flow']['xml_return']); ?>"> 
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
function GetNextTicketMessageID($ticket_id){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	$str_sql = "select count(ticketMessageid) from ticketmessage
		    where ticketid = $ticket_id";
	 
      $nm_select = $str_sql; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->dataset = array();
      if ($SCrx = $this->Db->Execute($nm_select)) 
      { 
          $SCy = 0; 
          $nm_count = $SCrx->FieldCount();
          while (!$SCrx->EOF)
          { 
                 for ($SCx = 0; $SCx < $nm_count; $SCx++)
                 { 
                        $this->dataset[$SCy] [$SCx] = $SCrx->fields[$SCx];
                 }
                 $SCy++; 
                 $SCrx->MoveNext();
          } 
          $SCrx->Close();
      } 
      elseif (isset($GLOBALS["NM_ERRO_IBASE"]) && $GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->dataset = false;
          $this->dataset_erro = $this->Db->ErrorMsg();
      } 

	$int_result  = $this->dataset [0][0]+1;
	return $int_result;
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function CloseTicket($ticket_id){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	if($ticket_id == ''){ return false; }
	
	$str_update = "update ticket set statusid = 'CLOSED'
	               where ticketid = $ticket_id";
	
     $nm_select = $str_update; 
         $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select;
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
         $rf = $this->Db->Execute($nm_select);
         if ($rf === false)
         {
             $this->Erro->mensagem (__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg());
             if ($this->Ini->sc_tem_trans_banco)
             {
                 $this->Db->RollbackTrans(); 
                 $this->Ini->sc_tem_trans_banco = false;
             }
             exit;
         }
         $rf->Close();
      
	
	return true;
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function TransferTicket($ticket_id,$new_ownerid){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	if($ticket_id == ''){ return false; }
	
	if($new_ownerid == ''){
		return false;	
	}
	
	$str_update = "update ticket 
	                  set ownerid = $new_ownerid,
	                      statusid = 'ASSIGNED'
	               where ticketid = $ticket_id";
	
     $nm_select = $str_update; 
         $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select;
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
         $rf = $this->Db->Execute($nm_select);
         if ($rf === false)
         {
             $this->Erro->mensagem (__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg());
             if ($this->Ini->sc_tem_trans_banco)
             {
                 $this->Db->RollbackTrans(); 
                 $this->Ini->sc_tem_trans_banco = false;
             }
             exit;
         }
         $rf->Close();
      	
	
	return true;
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function UpdateTicketStatus($ticket_id,$status_id = 'REPLIED'){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  
	
	if($ticket_id == ''){ return false; }

	$str_update = "update ticket set Statusid = '$status_id'
	               where ticketid = $ticket_id";
	
     $nm_select = $str_update; 
         $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select;
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
         $rf = $this->Db->Execute($nm_select);
         if ($rf === false)
         {
             $this->Erro->mensagem (__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg());
             if ($this->Ini->sc_tem_trans_banco)
             {
                 $this->Db->RollbackTrans(); 
                 $this->Ini->sc_tem_trans_banco = false;
             }
             exit;
         }
         $rf->Close();
      
	
	return true;
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function GetTicketSubject($ticket_id){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	if($ticket_id == ''){ return false; }
	
	$str_sql = "SELECT subject FROM ticket where ticketid = $ticket_id";
	 
      $nm_select = $str_sql; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->dataset = array();
      if ($SCrx = $this->Db->Execute($nm_select)) 
      { 
          $SCy = 0; 
          $nm_count = $SCrx->FieldCount();
          while (!$SCrx->EOF)
          { 
                 for ($SCx = 0; $SCx < $nm_count; $SCx++)
                 { 
                        $this->dataset[$SCy] [$SCx] = $SCrx->fields[$SCx];
                 }
                 $SCy++; 
                 $SCrx->MoveNext();
          } 
          $SCrx->Close();
      } 
      elseif (isset($GLOBALS["NM_ERRO_IBASE"]) && $GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->dataset = false;
          $this->dataset_erro = $this->Db->ErrorMsg();
      } 

	
	return $this->dataset [0][0];
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function GetTicketStatus($ticket_id){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	if($ticket_id == ''){ return false; }
	
	$str_sql = "SELECT statusid FROM ticket where ticketid = $ticket_id";
	 
      $nm_select = $str_sql; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->dataset = array();
      if ($SCrx = $this->Db->Execute($nm_select)) 
      { 
          $SCy = 0; 
          $nm_count = $SCrx->FieldCount();
          while (!$SCrx->EOF)
          { 
                 for ($SCx = 0; $SCx < $nm_count; $SCx++)
                 { 
                        $this->dataset[$SCy] [$SCx] = $SCrx->fields[$SCx];
                 }
                 $SCy++; 
                 $SCrx->MoveNext();
          } 
          $SCrx->Close();
      } 
      elseif (isset($GLOBALS["NM_ERRO_IBASE"]) && $GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->dataset = false;
          $this->dataset_erro = $this->Db->ErrorMsg();
      } 

	
	return $this->dataset [0][0];
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function GetTicketRating($ticket_id)
{
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  
	if($ticket_id == ''){ return false; }
	
	$str_sql = "SELECT customerrating FROM ticket where ticketid = $ticket_id";
	 
      $nm_select = $str_sql; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->dataset = array();
      if ($SCrx = $this->Db->Execute($nm_select)) 
      { 
          $SCy = 0; 
          $nm_count = $SCrx->FieldCount();
          while (!$SCrx->EOF)
          { 
                 for ($SCx = 0; $SCx < $nm_count; $SCx++)
                 { 
                        $this->dataset[$SCy] [$SCx] = $SCrx->fields[$SCx];
                 }
                 $SCy++; 
                 $SCrx->MoveNext();
          } 
          $SCrx->Close();
      } 
      elseif (isset($GLOBALS["NM_ERRO_IBASE"]) && $GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->dataset = false;
          $this->dataset_erro = $this->Db->ErrorMsg();
      } 

	
	return $this->dataset [0][0];
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function GetOperatorName($staffid){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	if($staffid == ''){ return false; }
	
	$str_sql = "SELECT staffname FROM staff 
	            WHERE staffid = $staffid";
	            
	 
      $nm_select = $str_sql; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->dataset = array();
      if ($SCrx = $this->Db->Execute($nm_select)) 
      { 
          $SCy = 0; 
          $nm_count = $SCrx->FieldCount();
          while (!$SCrx->EOF)
          { 
                 for ($SCx = 0; $SCx < $nm_count; $SCx++)
                 { 
                        $this->dataset[$SCy] [$SCx] = $SCrx->fields[$SCx];
                 }
                 $SCy++; 
                 $SCrx->MoveNext();
          } 
          $SCrx->Close();
      } 
      elseif (isset($GLOBALS["NM_ERRO_IBASE"]) && $GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->dataset = false;
          $this->dataset_erro = $this->Db->ErrorMsg();
      } 

	
	return $this->dataset [0][0];
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function GetCustomerTicket($ticket_id){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	if($ticket_id == ''){ return false; }
	
	$str_sql = "SELECT customerid FROM ticket where ticketid = $ticket_id";
	 
      $nm_select = $str_sql; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->dataset = array();
      if ($SCrx = $this->Db->Execute($nm_select)) 
      { 
          $SCy = 0; 
          $nm_count = $SCrx->FieldCount();
          while (!$SCrx->EOF)
          { 
                 for ($SCx = 0; $SCx < $nm_count; $SCx++)
                 { 
                        $this->dataset[$SCy] [$SCx] = $SCrx->fields[$SCx];
                 }
                 $SCy++; 
                 $SCrx->MoveNext();
          } 
          $SCrx->Close();
      } 
      elseif (isset($GLOBALS["NM_ERRO_IBASE"]) && $GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->dataset = false;
          $this->dataset_erro = $this->Db->ErrorMsg();
      } 

	
	return $this->dataset [0][0];
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function GetCustomerEmail($customerid){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	if($customerid== ''){ return false; }
	
	$str_sql = "SELECT customeremail FROM customer where customerid = $customerid";
	 
      $nm_select = $str_sql; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->dataset = array();
      if ($SCrx = $this->Db->Execute($nm_select)) 
      { 
          $SCy = 0; 
          $nm_count = $SCrx->FieldCount();
          while (!$SCrx->EOF)
          { 
                 for ($SCx = 0; $SCx < $nm_count; $SCx++)
                 { 
                        $this->dataset[$SCy] [$SCx] = $SCrx->fields[$SCx];
                 }
                 $SCy++; 
                 $SCrx->MoveNext();
          } 
          $SCrx->Close();
      } 
      elseif (isset($GLOBALS["NM_ERRO_IBASE"]) && $GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->dataset = false;
          $this->dataset_erro = $this->Db->ErrorMsg();
      } 

	
	return $this->dataset [0][0];
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function GetCustomerName($customerid){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	if($customerid== ''){ return false; }
	
	$str_sql = "SELECT customername FROM customer where customerid = $customerid";
	 
      $nm_select = $str_sql; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->dataset = array();
      if ($SCrx = $this->Db->Execute($nm_select)) 
      { 
          $SCy = 0; 
          $nm_count = $SCrx->FieldCount();
          while (!$SCrx->EOF)
          { 
                 for ($SCx = 0; $SCx < $nm_count; $SCx++)
                 { 
                        $this->dataset[$SCy] [$SCx] = $SCrx->fields[$SCx];
                 }
                 $SCy++; 
                 $SCrx->MoveNext();
          } 
          $SCrx->Close();
      } 
      elseif (isset($GLOBALS["NM_ERRO_IBASE"]) && $GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->dataset = false;
          $this->dataset_erro = $this->Db->ErrorMsg();
      } 

	
	return $this->dataset [0][0];
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function SetTicketOwner($ticket_id,$staffid){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	if($ticket_id == '' || $staffid == ''){ return false; }
	
	$str_sql = "SELECT ownerid FROM ticket 
	            WHERE ticketid = $ticket_id";
	            
	 
      $nm_select = $str_sql; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->dataset = array();
      if ($SCrx = $this->Db->Execute($nm_select)) 
      { 
          $SCy = 0; 
          $nm_count = $SCrx->FieldCount();
          while (!$SCrx->EOF)
          { 
                 for ($SCx = 0; $SCx < $nm_count; $SCx++)
                 { 
                        $this->dataset[$SCy] [$SCx] = $SCrx->fields[$SCx];
                 }
                 $SCy++; 
                 $SCrx->MoveNext();
          } 
          $SCrx->Close();
      } 
      elseif (isset($GLOBALS["NM_ERRO_IBASE"]) && $GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->dataset = false;
          $this->dataset_erro = $this->Db->ErrorMsg();
      } 

	
	if($this->dataset [0][0] == false){
	
		$str_update = "update ticket set ownerid = $staffid
		               where ticketid = $ticket_id";
		
     $nm_select = $str_update; 
         $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select;
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
         $rf = $this->Db->Execute($nm_select);
         if ($rf === false)
         {
             $this->Erro->mensagem (__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg());
             if ($this->Ini->sc_tem_trans_banco)
             {
                 $this->Db->RollbackTrans(); 
                 $this->Ini->sc_tem_trans_banco = false;
             }
             exit;
         }
         $rf->Close();
      	
		return true;
	}
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function LoadStaff($staffid){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	if($staffid == ''){ return false; }

	$str_sql = "SELECT
			staffid,
			staffname,
			staffemail,
			staffpassword,
			adminflag,
			stafflanguage,
			signature
		    FROM
			staff
		    WHERE
			staffemail = '$staffid'";

	 
      $nm_select = $str_sql; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->dataset = array();
      if ($SCrx = $this->Db->Execute($nm_select)) 
      { 
          $SCy = 0; 
          $nm_count = $SCrx->FieldCount();
          while (!$SCrx->EOF)
          { 
                 for ($SCx = 0; $SCx < $nm_count; $SCx++)
                 { 
                        $this->dataset[$SCy] [$SCx] = $SCrx->fields[$SCx];
                 }
                 $SCy++; 
                 $SCrx->MoveNext();
          } 
          $SCrx->Close();
      } 
      elseif (isset($GLOBALS["NM_ERRO_IBASE"]) && $GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->dataset = false;
          $this->dataset_erro = $this->Db->ErrorMsg();
      } 

	
	if(empty($this->dataset [0][0])){ return false; }
	
	$arr_staff = array();
	$arr_staff['staffid']      = (!empty($this->dataset [0][0]))?$this->dataset [0][0]:'';
	$arr_staff['staffname']    = (!empty($this->dataset [0][1]))?$this->dataset [0][1]:'';
	$arr_staff['staffemail']   = (!empty($this->dataset [0][2]))?$this->dataset [0][2]:'';
	$arr_staff['staffpassword']= (!empty($this->dataset [0][3]))?$this->dataset [0][3]:'';
	$arr_staff['adminflag']    = (!empty($this->dataset [0][4]))?$this->dataset [0][4]:'';
	$arr_staff['stafflanguage']     = (!empty($this->dataset [0][5]))?$this->dataset [0][5]:'';
	$arr_staff['signature']    = (!empty($this->dataset [0][6]))?$this->dataset [0][6]:'-------------------<br />'.$arr_staff['staffname'];

	$str_sql = "SELECT categoryid FROM staff_categories
                    WHERE staff_staffid = ".$arr_staff['staffid'];
	 
      $nm_select = $str_sql; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->dscategories = array();
      if ($SCrx = $this->Db->Execute($nm_select)) 
      { 
          $SCy = 0; 
          $nm_count = $SCrx->FieldCount();
          while (!$SCrx->EOF)
          { 
                 for ($SCx = 0; $SCx < $nm_count; $SCx++)
                 { 
                        $this->dscategories[$SCy] [$SCx] = $SCrx->fields[$SCx];
                 }
                 $SCy++; 
                 $SCrx->MoveNext();
          } 
          $SCrx->Close();
      } 
      elseif (isset($GLOBALS["NM_ERRO_IBASE"]) && $GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->dscategories = false;
          $this->dscategories_erro = $this->Db->ErrorMsg();
      } 


	$str_categories = '';
	foreach($this->dscategories  as $arr_categories){
		$str_categories.= $arr_categories[0].","; 
	}
	$str_categories = substr($str_categories,0,strlen($str_categories)-1);
	
	$arr_staff['categories']     = ($str_categories!='')?$str_categories:'null';
		
	return $arr_staff;
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function LoadSettings(){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	$str_sql = "SELECT
			smtpserver,
			smtpuser,
			smtppassword,
			assigmentmode,
			publicticketsopening,
			definedparameters,
			broadcastmessages,
			defaultpriority,
			smtpsecurityflag,
			smtpport,
			urltrackingscreen,
			defaultlanguage,
			urlconfirmationscreen,
			emailaccount,
			sys_version
		   FROM
			systemsettings
		   WHERE
			id = 1";
			
	 
      $nm_select = $str_sql; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->dataset = array();
      if ($SCrx = $this->Db->Execute($nm_select)) 
      { 
          $SCy = 0; 
          $nm_count = $SCrx->FieldCount();
          while (!$SCrx->EOF)
          { 
                 for ($SCx = 0; $SCx < $nm_count; $SCx++)
                 { 
                        $this->dataset[$SCy] [$SCx] = $SCrx->fields[$SCx];
                 }
                 $SCy++; 
                 $SCrx->MoveNext();
          } 
          $SCrx->Close();
      } 
      elseif (isset($GLOBALS["NM_ERRO_IBASE"]) && $GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->dataset = false;
          $this->dataset_erro = $this->Db->ErrorMsg();
      } 

	
	$arr_load = array();
	$arr_load['smtpserver']            = (!empty($this->dataset [0][0]))?$this->dataset [0][0]:'';
	$arr_load['smtpuser']              = (!empty($this->dataset [0][1]))?$this->dataset [0][1]:'';
	$arr_load['smtppassword']          = (!empty($this->dataset [0][2]))?$this->dataset [0][2]:'';
	$arr_load['assigmentmode']         = (!empty($this->dataset [0][3]))?$this->dataset [0][3]:'';
	$arr_load['publicticketsopening']  = (!empty($this->dataset [0][4]))?$this->dataset [0][4]:'';
	$arr_load['definedparameters']     = (!empty($this->dataset [0][5]))?$this->dataset [0][5]:'';
	$arr_load['broadcastmessages']     = (!empty($this->dataset [0][6]))?$this->dataset [0][6]:'';
	$arr_load['defaultpriority']       = (!empty($this->dataset [0][7]))?$this->dataset [0][7]:'';
	$arr_load['smtpsecurityflag']      = (!empty($this->dataset [0][8]))?$this->dataset [0][8]:'';
	$arr_load['smtpport']              = (!empty($this->dataset [0][9]))?$this->dataset [0][9]:'';
	$arr_load['urltrackingscreen']     = (!empty($this->dataset [0][10]))?$this->dataset [0][10]:'';
	$arr_load['defaultlanguage']       = (!empty($this->dataset [0][11]))?$this->dataset [0][11]:'en_us;en_us';
	$arr_load['urlconfirmationscreen'] = (!empty($this->dataset [0][12]))?$this->dataset [0][12]:'';	
	$arr_load['emailaccount'] 	   = (!empty($this->dataset [0][13]))?$this->dataset [0][13]:'';	
	$arr_load['version'] 	  	   = (!empty($this->dataset [0][14]))?$this->dataset [0][14]:'';	

	$_SESSION['ticketsettings'] = $arr_load;
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function getCustomerByEmail($customer_email){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	if(empty($customer_email)){ return false; }

	$str_sql = "SELECT customerid, customername, customerpassword
                    FROM customer where customeremail = '$customer_email'";

	 
      $nm_select = $str_sql; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->dataset = array();
      if ($SCrx = $this->Db->Execute($nm_select)) 
      { 
          $SCy = 0; 
          $nm_count = $SCrx->FieldCount();
          while (!$SCrx->EOF)
          { 
                 for ($SCx = 0; $SCx < $nm_count; $SCx++)
                 { 
                        $this->dataset[$SCy] [$SCx] = $SCrx->fields[$SCx];
                 }
                 $SCy++; 
                 $SCrx->MoveNext();
          } 
          $SCrx->Close();
      } 
      elseif (isset($GLOBALS["NM_ERRO_IBASE"]) && $GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->dataset = false;
          $this->dataset_erro = $this->Db->ErrorMsg();
      } 

	
	$arr_customer = array();
	$arr_customer['customerid']      = (!empty($this->dataset [0][0]))?$this->dataset [0][0]:'';
	$arr_customer['customername']    = (!empty($this->dataset [0][1]))?$this->dataset [0][1]:'';
	$arr_customer['customerpassword']= (!empty($this->dataset [0][2]))?$this->dataset [0][2]:'';

	return $arr_customer;
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function getStaffById($staff_id){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	if(empty($staff_id)){ return false; }

	$str_sql = "SELECT staffid, staffname, staffemail
                    FROM staff where staffid = '$staff_id'";

	 
      $nm_select = $str_sql; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->dataset = array();
      if ($SCrx = $this->Db->Execute($nm_select)) 
      { 
          $SCy = 0; 
          $nm_count = $SCrx->FieldCount();
          while (!$SCrx->EOF)
          { 
                 for ($SCx = 0; $SCx < $nm_count; $SCx++)
                 { 
                        $this->dataset[$SCy] [$SCx] = $SCrx->fields[$SCx];
                 }
                 $SCy++; 
                 $SCrx->MoveNext();
          } 
          $SCrx->Close();
      } 
      elseif (isset($GLOBALS["NM_ERRO_IBASE"]) && $GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->dataset = false;
          $this->dataset_erro = $this->Db->ErrorMsg();
      } 

	
	$arr_staff = array();
	$arr_staff['staffid']      = (!empty($this->dataset [0][0]))?$this->dataset [0][0]:'';
	$arr_staff['staffname']    = (!empty($this->dataset [0][1]))?$this->dataset [0][1]:'';
	$arr_staff['staffemail']   = (!empty($this->dataset [0][2]))?$this->dataset [0][2]:'';

	return $arr_staff;
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function getStaffByEmail($staff_email){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	if(empty($staff_email)){ return false; }

	$str_sql = "SELECT staffid, staffname, staffpassword
                    FROM staff where staffemail = '$staff_email'";

	 
      $nm_select = $str_sql; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->dataset = array();
      if ($SCrx = $this->Db->Execute($nm_select)) 
      { 
          $SCy = 0; 
          $nm_count = $SCrx->FieldCount();
          while (!$SCrx->EOF)
          { 
                 for ($SCx = 0; $SCx < $nm_count; $SCx++)
                 { 
                        $this->dataset[$SCy] [$SCx] = $SCrx->fields[$SCx];
                 }
                 $SCy++; 
                 $SCrx->MoveNext();
          } 
          $SCrx->Close();
      } 
      elseif (isset($GLOBALS["NM_ERRO_IBASE"]) && $GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->dataset = false;
          $this->dataset_erro = $this->Db->ErrorMsg();
      } 

	
	$arr_staff = array();
	$arr_staff['staffid']      = (!empty($this->dataset [0][0]))?$this->dataset [0][0]:'';
	$arr_staff['staffname']    = (!empty($this->dataset [0][1]))?$this->dataset [0][1]:'';
	$arr_staff['staffpassword']= (!empty($this->dataset [0][2]))?$this->dataset [0][2]:'';

	return $arr_staff;
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function SetTrackingId($ticket, $mode='')
{
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  
	
	$trk_id = strtoupper(substr(md5($ticket),0,10));
	
     $nm_select = "UPDATE ticket SET tickettrack = '$trk_id' WHERE ticketid = $ticket"; 
         $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select;
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
         $rf = $this->Db->Execute($nm_select);
         if ($rf === false)
         {
             $this->Erro->mensagem (__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg());
             if ($this->Ini->sc_tem_trans_banco)
             {
                 $this->Db->RollbackTrans(); 
                 $this->Ini->sc_tem_trans_banco = false;
             }
             exit;
         }
         $rf->Close();
      

	if($mode == 'RELATIVE') {
	
	$url_tracking = '../ctrl_tracking_tickets/ctrl_tracking_tickets.php'. '?v_trackingid=' . $trk_id;

	} else {
	
	$url_tracking = $_SESSION['TicketSettings']['UrlTrackingScreen'] . '?v_trackingid=' . $trk_id;
	
	}
	return($url_tracking);
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function GetTrackingId($ticket)
{
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  
		
	$trk_id = strtoupper(substr(md5($ticket),0,10));
	return($trk_id);

$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function VersionDemo(){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	$arr_load = (!empty($_SESSION['TicketSettings']))?$_SESSION['TicketSettings']:false;
	if($arr_load == false){
		
 if (!isset($this->Campos_Mens_erro)){$this->Campos_Mens_erro = "";}
 if (!empty($this->Campos_Mens_erro)){$this->Campos_Mens_erro .= "<br>";}$this->Campos_Mens_erro .= $this->Ini->Nm_lang['lang_error_blocked_demo'];
;
	}
	$bl_version = $arr_load['Version'];
	
	if($bl_version == 'demo'){
		
 if (!isset($this->Campos_Mens_erro)){$this->Campos_Mens_erro = "";}
 if (!empty($this->Campos_Mens_erro)){$this->Campos_Mens_erro .= "<br>";}$this->Campos_Mens_erro .= $this->Ini->Nm_lang['lang_error_blocked_demo'];
;
	}
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function DefinedParameters(){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	$definedParameters  = (!empty($_SESSION['TicketSettings']['DefinedParameters']))?
				$_SESSION['TicketSettings']['DefinedParameters']:false;

	if($definedParameters != 'Y'){
		 if (!isset($this->Campos_Mens_erro) || empty($this->Campos_Mens_erro))
 {
$this->nmgp_redireciona_form($this->Ini->path_link . "" . SC_dir_app_name('form_systemsettings') . "/form_systemsettings.php", $this->nm_location, "","_self", 440, 630, "ret_self");
 };
	}
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function scTicketStart(){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  
	$str_file = '../../../devel/conf/scriptcase/sc_tickets_start';
	
	if(is_file($str_file)){
	
		$str_sql = "SELECT
				strftime('%Y', ticketdate) as Year,
				strftime('%m', ticketdate) as Month
			    FROM
			        ticket
			    order by    
			        ticketdate desc";
		 
      $nm_select = $str_sql; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->dataset = array();
      if ($SCrx = $this->Db->Execute($nm_select)) 
      { 
          $SCy = 0; 
          $nm_count = $SCrx->FieldCount();
          while (!$SCrx->EOF)
          { 
                 for ($SCx = 0; $SCx < $nm_count; $SCx++)
                 { 
                        $this->dataset[$SCy] [$SCx] = $SCrx->fields[$SCx];
                 }
                 $SCy++; 
                 $SCrx->MoveNext();
          } 
          $SCrx->Close();
      } 
      elseif (isset($GLOBALS["NM_ERRO_IBASE"]) && $GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->dataset = false;
          $this->dataset_erro = $this->Db->ErrorMsg();
      } 

		
		$last_month  = $this->dataset [0][1];
		$last_year   = $this->dataset [0][0];
		
		if($last_year == date('Y') && $last_month < date('m')){
		
			$diff = date('m') - $last_month;
			
			$str_sql = "update ticket set
			ticketdate = datetime(ticketdate ,'+$diff month'),
			ticketlastupdate = datetime(ticketlastupdate,'+$diff month')";
			
     $nm_select = $str_sql; 
         $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select;
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
         $rf = $this->Db->Execute($nm_select);
         if ($rf === false)
         {
             $this->Erro->mensagem (__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg());
             if ($this->Ini->sc_tem_trans_banco)
             {
                 $this->Db->RollbackTrans(); 
                 $this->Ini->sc_tem_trans_banco = false;
             }
             exit;
         }
         $rf->Close();
      
			
			$str_sql = "update ticketmessage set
			ticketdate = datetime(ticketdate ,'+$diff month')";
			
     $nm_select = $str_sql; 
         $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select;
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
         $rf = $this->Db->Execute($nm_select);
         if ($rf === false)
         {
             $this->Erro->mensagem (__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg());
             if ($this->Ini->sc_tem_trans_banco)
             {
                 $this->Db->RollbackTrans(); 
                 $this->Ini->sc_tem_trans_banco = false;
             }
             exit;
         }
         $rf->Close();
      
			
		}
		elseif($last_year < date('Y')){
			$diff = date('Y') - $last_year;
			
			$str_sql = "update ticket set
			ticketdate = datetime(ticketdate ,'+$diff year'),
			ticketlastupdate = datetime(ticketlastupdate,'+$diff year')";
			
     $nm_select = $str_sql; 
         $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select;
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
         $rf = $this->Db->Execute($nm_select);
         if ($rf === false)
         {
             $this->Erro->mensagem (__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg());
             if ($this->Ini->sc_tem_trans_banco)
             {
                 $this->Db->RollbackTrans(); 
                 $this->Ini->sc_tem_trans_banco = false;
             }
             exit;
         }
         $rf->Close();
      
			
			$str_sql = "update ticketmessage set
			ticketdate = datetime(ticketdate ,'+$diff year')";
			
     $nm_select = $str_sql; 
         $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_select;
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
         $rf = $this->Db->Execute($nm_select);
         if ($rf === false)
         {
             $this->Erro->mensagem (__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg());
             if ($this->Ini->sc_tem_trans_banco)
             {
                 $this->Db->RollbackTrans(); 
                 $this->Ini->sc_tem_trans_banco = false;
             }
             exit;
         }
         $rf->Close();
      
		
		}
		unlink($str_file);
	
	}
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
function M_Warning($str_title, $str_message, $int_left=100, $int_top=100, $int_width=400,$action='RETURN'){
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'on';
  

	$str_possition = "position:absolute; left:".$int_left."px; top:".$int_top."px; ";
 	if($action == 'RETURN') $str_possition = '';
 
 $str_html = "
	<table id='scwarningid' cellspacing='0' cellpading='0' 
	 style='".$str_possition." border:1px solid #d1dceb; width:".$int_width."px; z-index:300; background-color:#FFFFFF' >
	<tr>
	  <td background='../_lib/img/bgGrid.png' style='font-size:12px; font-weight:bolder;' >
	  <a href='#' onclick=\"document.getElementById('scwarningid').style.display='none';\">
	    <img src='../_lib/img/grp__NM__ok.gif' border='0' /></a>
	  ".$str_title."
	  </td>
	</tr>
	<tr>
	  <td style='font-size:12px; padding:10px'>".$str_message."  
	  </td>
	</tr>
	</table>
"; 
 
 	if($action == 'RETURN') return $str_html;
 	else echo $str_html;
 
 
 
$_SESSION['scriptcase']['grid_customer_ticket_flow']['contr_erro'] = 'off';
}
}

?>
