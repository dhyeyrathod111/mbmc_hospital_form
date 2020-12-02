<?php 
/**
 * @author Dhyey Rathod
 */
class Emailtrigger
{
    private $_url =  FALSE;

    private $_user = FALSE;

    private $_password = FALSE;

    private $_from_email = FALSE ;

    private $__codeigniter = FALSE ;

    public function __construct()
    {
        $this->_url = 'https://api.sendgrid.com/api/mail.send.json';
        $this->_user = 'dhyeyrathod111';
        $this->_password = 'Dhyey1995!((%';
        $this->_from_email = 'dhyeyrathod111@gmail.com';
        $this->__codeigniter =& get_instance();
    }
    public function sendMail($email_stack = array())
    {
        $mail_config['api_user'] = $this->_user ;
        $mail_config['api_key'] = $this->_password ;
		$mail_config['to'] = $email_stack['to'];
        if (!empty($mail_config['cc'])) $mail_config['cc'] = $email_stack['cc']; 
        $mail_config['subject'] = $email_stack['subject'];
        $mail_config['html'] = $email_stack['body'] ;
        $mail_config['from'] = $this->_from_email ;
        $session = curl_init($this->_url);
        curl_setopt($session, CURLOPT_POST, true);
        curl_setopt($session, CURLOPT_POSTFIELDS, $mail_config);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($session);
        curl_close($session);
        return json_decode($response);
    }
    public function codeigniter_mail($email_stack=array())
    {
        $config = array();
        $config['protocol']    = 'smtp';
        $config['smtp_host']    = 'ssl://smtp.googlemail.com';   
        $config['smtp_port']    = '465';                    
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = 'developmentteamaarav@gmail.com';
        $config['smtp_pass']    = '@dmin123';
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'html'; 
        $config['validation'] = TRUE;
        $this->__codeigniter->email->initialize($config); 
        $this->__codeigniter->email->from('developmentteamaarav@gmail.com','MBMC');
        $this->__codeigniter->email->to($email_stack['to']);
        $this->__codeigniter->email->cc([ 'dhyeyrathod111@gmail.com','ankit.naik@aaravsoftware.com' ]);
        $this->__codeigniter->email->subject($email_stack['subject']);
        $this->__codeigniter->email->message($email_stack['body']);
        if ($this->__codeigniter->email->send()) {
            return TRUE;
        } else {
            echo $this->__codeigniter->email->print_debugger();
        }
    }
}
