<?php

class grid_tickets_admin_total
{
   var $Db;
   var $Erro;
   var $Ini;
   var $Lookup;

   var $nm_data;

   //----- 
   function __construct($sc_page)
   {
      $this->sc_page = $sc_page;
      $this->nm_data = new nm_data("en_us");
      if (isset($_SESSION['sc_session'][$this->sc_page]['grid_tickets_admin']['campos_busca']) && !empty($_SESSION['sc_session'][$this->sc_page]['grid_tickets_admin']['campos_busca']))
      { 
          $Busca_temp = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tickets_admin']['campos_busca'];
          if ($_SESSION['scriptcase']['charset'] != "UTF-8")
          {
              $Busca_temp = NM_conv_charset($Busca_temp, $_SESSION['scriptcase']['charset'], "UTF-8");
          }
          $this->ticket_tickettrack = (isset($Busca_temp['ticket_tickettrack'])) ? $Busca_temp['ticket_tickettrack'] : ""; 
          $tmp_pos = (is_string($this->ticket_tickettrack)) ? strpos($this->ticket_tickettrack, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->ticket_tickettrack))
          {
              $this->ticket_tickettrack = substr($this->ticket_tickettrack, 0, $tmp_pos);
          }
          $this->ticket_ticketpriorityid = (isset($Busca_temp['ticket_ticketpriorityid'])) ? $Busca_temp['ticket_ticketpriorityid'] : ""; 
          $tmp_pos = (is_string($this->ticket_ticketpriorityid)) ? strpos($this->ticket_ticketpriorityid, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->ticket_ticketpriorityid))
          {
              $this->ticket_ticketpriorityid = substr($this->ticket_ticketpriorityid, 0, $tmp_pos);
          }
          $this->customer_customername = (isset($Busca_temp['customer_customername'])) ? $Busca_temp['customer_customername'] : ""; 
          $tmp_pos = (is_string($this->customer_customername)) ? strpos($this->customer_customername, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->customer_customername))
          {
              $this->customer_customername = substr($this->customer_customername, 0, $tmp_pos);
          }
          $this->ticket_categoryid = (isset($Busca_temp['ticket_categoryid'])) ? $Busca_temp['ticket_categoryid'] : ""; 
          $tmp_pos = (is_string($this->ticket_categoryid)) ? strpos($this->ticket_categoryid, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->ticket_categoryid))
          {
              $this->ticket_categoryid = substr($this->ticket_categoryid, 0, $tmp_pos);
          }
          $this->ticket_ownerid = (isset($Busca_temp['ticket_ownerid'])) ? $Busca_temp['ticket_ownerid'] : ""; 
          $tmp_pos = (is_string($this->ticket_ownerid)) ? strpos($this->ticket_ownerid, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->ticket_ownerid))
          {
              $this->ticket_ownerid = substr($this->ticket_ownerid, 0, $tmp_pos);
          }
          $this->ticket_ticketlastupdate = (isset($Busca_temp['ticket_ticketlastupdate'])) ? $Busca_temp['ticket_ticketlastupdate'] : ""; 
          $tmp_pos = (is_string($this->ticket_ticketlastupdate)) ? strpos($this->ticket_ticketlastupdate, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->ticket_ticketlastupdate))
          {
              $this->ticket_ticketlastupdate = substr($this->ticket_ticketlastupdate, 0, $tmp_pos);
          }
          $ticket_ticketlastupdate_2 = (isset($Busca_temp['ticket_ticketlastupdate_input_2'])) ? $Busca_temp['ticket_ticketlastupdate_input_2'] : ""; 
          $this->ticket_ticketlastupdate_2 = $ticket_ticketlastupdate_2; 
          $this->ticket_statusid = (isset($Busca_temp['ticket_statusid'])) ? $Busca_temp['ticket_statusid'] : ""; 
          $tmp_pos = (is_string($this->ticket_statusid)) ? strpos($this->ticket_statusid, "##@@") : false;
          if ($tmp_pos !== false && !is_array($this->ticket_statusid))
          {
              $this->ticket_statusid = substr($this->ticket_statusid, 0, $tmp_pos);
          }
      } 
   }

   //---- 
   function quebra_geral()
   {
      global $nada, $nm_lang , $ticket_categoryid;
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tickets_admin']['contr_total_geral'] == "OK") 
      { 
          return; 
      } 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tickets_admin']['tot_geral'] = array() ;  
      if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_access))
      { 
          $nm_comando = "select count(*) from " . $this->Ini->nm_tabela . " " . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tickets_admin']['where_pesq']; 
      } 
      elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_mssql))
      { 
          $nm_comando = "select count(*) from " . $this->Ini->nm_tabela . " " . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tickets_admin']['where_pesq']; 
      } 
      else 
      { 
          $nm_comando = "select count(*) from " . $this->Ini->nm_tabela . " " . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tickets_admin']['where_pesq']; 
      } 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_comando;
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = '';
      if (!$rt = $this->Db->Execute($nm_comando)) 
      { 
         $this->Erro->mensagem (__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg()); 
         exit ; 
      }
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tickets_admin']['tot_geral'][0] = "" . $this->Ini->Nm_lang['lang_msgs_totl'] . ""; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tickets_admin']['tot_geral'][1] = $rt->fields[0] ; 
      $rt->Close(); 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tickets_admin']['contr_total_geral'] = "OK";
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
}

?>
