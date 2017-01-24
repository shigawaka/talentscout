<?php
return array(
    // set your paypal credential
    'client_id' => 'Ab7ls3Aue4_kMNi3tTy_CK_be_0bgNgDqwreBu1I4V4mJzhhMT_xvLGmEFJsi0JMiQb4F-E_8wg67Itc',
    'secret' => 'ELcZXtEA5golf6ptaOQNcEQkRYMtH8LUHBsGYaLsjhjIc9LCHalmuXLjKeJpqV983cAlxbrax4zPU-YZ',
    /**
     * SDK configuration 
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => 'sandbox',
        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 30,
        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,
        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',
        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);

?>