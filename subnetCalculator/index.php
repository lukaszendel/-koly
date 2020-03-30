<!DOCTYPE html>
<html>
    <head>
        <title>IPV4 Subnet Calculator</title>
        <link rel="stylesheet" href="style.css" type="text/css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
    <center>
        <form method="get" action="<?php print $_SERVER['PHP_SELF'] ?> ">
            <br><br>
            <table>
                <tr><td>
                        <p>Welcome to IPv4 Subnet Calculator!</p>
                    </td></tr>
            </table>
            <BR>
            <table>
                <tr>
                    <td>Network:</td>
                    <td><input type="text" name="network" value="<?php echo $_REQUEST['network'] ?>" size="31" maxlength="64"></td>
                    <td><input type="submit" value="Calculate" name="subnetcalc">

                    </td>
                </tr>
            </table></form><br>


        <?php
//Vytvoření/start table
        print "<table cellpadding=\"2\">\n<COL span=\"4\" align=\"left\">\n";

        $end = '</table><table>
      <tr><td>
      </td></tr></table></center></body></html>';

        if (empty($_REQUEST['network'])) {
            //tip na použití
            tr('Use IP/Network: ', '10.0.0.1/22');
            print $end;
            exit;
        }

        function tr() {
            echo "\t<tr>";
            for ($i = 0; $i < func_num_args(); $i++)
                echo "<td>" . func_get_arg($i) . "</td>";
            echo "</tr>\n";
        }

        ini_set('display_errors', 1);

        $maxSubNets = '2048'; // hlídá přesažení max počtu

        $superNet = $_REQUEST['network'];
        $superNetMask = ''; // nepovinné


// Počítání supernet a cdr
        if (preg_match('~/~', $superNet)) {  //pokud je cdr typ mask
            $charHost = inet_pton(strtok($superNet, '/'));
            $charMask = _cdr2Char(strtok('/'), strlen($charHost));
        } else {
            $charHost = inet_pton($superNet);
            $charMask = inet_pton($superNetMask);
        }


// Jedna host maska použitá pro operaci s bity hostmin a hostmax
        $charHostMask = substr(_cdr2Char(127), -strlen($charHost));

        $charWC = ~$charMask; // Supernet wildcard maska
        $charNet = $charHost & $charMask; // Supernet network adresa
        $charBcst = $charNet | ~$charMask; // Supernet broadcast
        $charHostMin = $charNet | ~$charHostMask; // Minimum počet hostů
        $charHostMax = $charBcst & $charHostMask; // Maximum počet hostů
// Výsledek operace print
        tr('Network:', '<font color="blue">' . inet_ntop($charNet) . "/" . _char2Cdr($charMask) . "</font>");
        tr('Netmask:', '<font color="blue">' . inet_ntop($charMask) . " = /" . _char2Cdr($charMask) . "</font>");
        tr('Wildcard:', '<font color="blue">' . inet_ntop($charWC) . '</font>');
        tr('Broadcast:', '<font color="blue">' . inet_ntop($charBcst) . '</font>');
        tr('HostMin:', '<font color="blue">' . inet_ntop($charHostMin) . '</font>');
        tr('HostMax:', '<font color="blue">' . inet_ntop($charHostMax) . '</font>');




// Převeď pole integerů do dvojkové soustavyy
        function _packBytes($array) {
            foreach ($array as $byte) {
                $chars .= pack('C', $byte);
            }
            return $chars;
        }

// Převeď dvoujkovou soustavu na pole integerů
        function _unpackBytes($string) {
            return unpack('C*', $string);
        }

// Přidej pole unsigned integerů
        function _addBytes($array1, $array2) {
            $result = array();
            $carry = 0;
            foreach (array_reverse($array1, true) as $value1) {
                $value2 = array_pop($array2);
                if (empty($result)) {
                    $value2++;
                }
                $newValue = $value1 + $value2 + $carry;
                if ($newValue > 255) {
                    $newValue = $newValue - 256;
                    $carry = 1;
                } else {
                    $carry = 0;
                }
                array_unshift($result, $newValue);
            }
            return $result;
        }

        /* Funkce */

        function _cdr2Bin($cdrin, $len = 4) {
            if ($len > 4 || $cdrin > 32) { // Are we ipv6?
                return str_pad(str_pad("", $cdrin, "1"), 128, "0");
            } else {
                return str_pad(str_pad("", $cdrin, "1"), 32, "0");
            }
        }

        function _bin2Cdr($binin) {
            return strlen(rtrim($binin, "0"));
        }

        function _cdr2Char($cdrin, $len = 4) {
            $hex = _bin2Hex(_cdr2Bin($cdrin, $len));
            return _hex2Char($hex);
        }

        function _char2Cdr($char) {
            $bin = _hex2Bin(_char2Hex($char));
            return _bin2Cdr($bin);
        }

        function _hex2Char($hex) {
            return pack('H*', $hex);
        }

        function _char2Hex($char) {
            $hex = unpack('H*', $char);
            return array_pop($hex);
        }

        function _hex2Bin($hex) {
            $bin = '';
            for ($i = 0; $i < strlen($hex); $i++)
                $bin .= str_pad(decbin(hexdec($hex{$i})), 4, '0', STR_PAD_LEFT);
            return $bin;
        }

        function _bin2Hex($bin) {
            $hex = '';
            for ($i = strlen($bin) - 4; $i >= 0; $i -= 4)
                $hex .= dechex(bindec(substr($bin, $i, 4)));
            return strrev($hex);
        }
        ?>