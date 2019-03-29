<?php
// Conexão direta com banco SQL Server (sem mencionar nome do banco)
// Clecio Antao - 25/03/2019 // PAVILION / editando 2
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Clecio</title>
    </head>
    <body>

        <?php
        // CONEXÃO BANCO SQL SERVER
        $serverName = "192.168.0.10,1433";
        //$serverName = "PAVILION\CURSO2";
        $connOptions = array("UID" => "sa", "PWD" => "Proteu690201");
        $conn = sqlsrv_connect($serverName, $connOptions);


        // TESTA ACESSO E DRIVERS DO BANCO

        if ($conn === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        if ($client_info = sqlsrv_client_info($conn)) {   //nao seguir a dica do netbeans
            echo 'Conectou! <br>';

            foreach ($client_info as $key => $value) {
                echo $key . ": " . $value . "<br />";
            }
        } else {

            echo "Erro.<br />";
        }

        $server_info = sqlsrv_server_info($conn);
        if ($server_info) {
            foreach ($server_info as $key => $value) {
                echo $key . ": " . $value . "<br />";
            }
        } else {
            die(print_r(sqlsrv_errors(), true));
        }


        // FAZ CONEXÃO COM SYS.DATABASES

        $sql_a = "SELECT name FROM sys.databases where database_id > 4";

        $stmt_a = sqlsrv_query($conn, $sql_a);

        if ($stmt_a === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $dados1 = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        var_dump($dados1);
        ?>        
        <!-- FORMULARIO SELECIONAR O BANCO-->
        <br> Selecione o banco <br>
        <form action="" method="post" name="bancos" >
            <select name = "select_banco" onchange="document.forms['bancos'].submit();">
<?php
// ciclo para percorrer os elementos de um array
$i = 0;
while ($row = sqlsrv_fetch_array($stmt_a, SQLSRV_FETCH_ASSOC)) {

    $i++;
    ?>
                    <option value="<?php echo $row['name']; ?>" <?php echo isset($_POST['select_banco']) && $_POST['select_banco'] == $row['name'] ? ' selected="selected"' : ''; ?>><?php echo $row['name']; ?> </option>

                    <?php
                }
                ?>   

            </select> 

        </form>

<?php
$dados2 = filter_input_array(INPUT_POST, FILTER_DEFAULT);
var_dump($dados2);


//if (isset($_POST['select_banco'])) {
//    echo $_POST['select_banco'];
//}   
// FAZ CONEXÃO COM BANCO SELECIONADO
//$sql_use = "USE " . $_POST['select_banco']; // . $bancosel;
$sql_use = "USE CADASTRO";
$stmt_use = sqlsrv_query($conn, $sql_use);

if ($stmt_use == false) {
    echo '<br> Não conectou banco selecionado...<br>';
    die(print_r(sqlsrv_errors(), true));
}

// FAZ SELECT PARA LER TABELAS DO BANCO
$sql_table = "select * from INFORMATION_SCHEMA.TABLES";
$stmt_table = sqlsrv_query($conn, $sql_table);

if ($stmt_table == false) {
    echo '<br> Não conectou com banco.<br>';
}

// LISTA AS TABELAS
?>

        <br> Selecione a tabela <br>

        <form action="" method="post" name="tabelas" >

            <select name = "select_tabela" onchange="document.forms['tabelas'].submit();">
        <?php
        while ($row = sqlsrv_fetch_array($stmt_table, SQLSRV_FETCH_ASSOC)) {
            // echo $row['name'] . "<br />";
            $i++;
            ?>
                    <option value="<?php echo $row['TABLE_NAME']; ?>" <?php echo isset($_POST['select_tabela']) && $_POST['select_tabela'] == $row['TABLE_NAME'] ? ' selected="selected"' : ''; ?>><?php echo $row['TABLE_NAME']; ?> </option>

<?php } ?>  

            </select>

        </form>

        <?php
        // LISTAR CONTEUDO DAS TABELAS SELECIONADAS
        //select * from information_schema.columns where table_name = 'amigos'
        //$sql_table_sel = "select * from " . $dados2['select_tabela'];
        $sql_table_sel = "select * from information_schema.columns where table_name = '" . $dados2['select_tabela'] . "'";

        $stmt_table_sel = sqlsrv_query($conn, $sql_table_sel);

        echo $sql_table_sel;

        if ($stmt_table_sel == false) {
            echo '<br> Não conectou com a tabela.<br>';
        }
        
        ?>
        <table border=1>
            <tr>
                <td>COLUNA</td>
                <td>TIPO</td>
                
            </tr> 
        <?php
        while ($row = sqlsrv_fetch_array($stmt_table_sel, SQLSRV_FETCH_ASSOC)) {
    
         ?>
           
        <tr>
            <td><?php echo $row['COLUMN_NAME']; ?></td>
            <td><?php echo $row['DATA_TYPE']; ?></td>
            
        </tr>
        <?php
        }
        ?>
                </table>
                
                
        







    </body>
</html>


