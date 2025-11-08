<?php

class LogQueryHook {

    function log_queries() {
        $CI = &get_instance();
        $times = $CI->db->query_times; //QUERY TIME RETURNS IN SECONDS
        $output = null;
        $queries = $CI->db->queries;
        $date_now = date('Y-m-d h:i:sa');

        /** Spaces are given for proper alignment of text */
        if (count($queries) == 0) {
            $output .= $date_now . "  no queries\n";
        } else {
            foreach ($queries as $key => $query) {
                $took = round(doubleval($times[$key]), 3);
                // I need to improve this line to record time properly
                // and I don't know in which format codeginiter is giving me time either its is seconds or miliseconds.
                $output .= $date_now . "        ===[took:{$took}]\n";
                $query = str_replace(array("\r\n", "\r", "\n", "\\r", "\\n", "\\r\\n"), "\n                             ", "                             " . $query);
                $output .= $query . "\n\n\n";
            }
        }

        $CI->load->helper('file');
        if (!write_file(APPPATH . "/logs/queries.log.txt", $output, 'a+')) {
            log_message('debug', 'Unable to write query the file');
        }
    }

}

?>