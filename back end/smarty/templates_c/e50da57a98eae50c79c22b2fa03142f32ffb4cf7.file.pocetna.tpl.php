<?php /* Smarty version Smarty-3.1.13, created on 2018-06-05 16:51:17
         compiled from ".\pluginovi\Smarti\tpl\pocetna.tpl" */ ?>
<?php /*%%SmartyHeaderCode:186465adf8e5b947a91-40048273%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e50da57a98eae50c79c22b2fa03142f32ffb4cf7' => 
    array (
      0 => '.\\pluginovi\\Smarti\\tpl\\pocetna.tpl',
      1 => 1528217394,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '186465adf8e5b947a91-40048273',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5adf8e5ba35f49_21167291',
  'variables' => 
  array (
    'debugOn' => 0,
    'trenmod' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5adf8e5ba35f49_21167291')) {function content_5adf8e5ba35f49_21167291($_smarty_tpl) {?><html>
    
    <head>
        <title>Food Assistant</title>
        <link rel="stylesheet"  type="text/css" href="./pluginovi/CSS/SmartyCSS/pocetna.css" />
    </head>
    
    <body>
    
        <?php if ($_smarty_tpl->tpl_vars['debugOn']->value=='true'){?>
        <div id='secret' class='polje'>
            <h1>Odaberi opciju</h1>
            <div>
                <table>
                    <tr> <td>------------------------------</td> </tr>
                     <tr>
                         <td>
                            <form action="./index.php" method="POST">
                                <button>Zatvori</button> 
                            </form>
                         </td>
                     </tr>

                     <form action="./index.php" method="POST">
                        <tr>
                            <td>
                                   <button>Odaberi</button> 
                            </td>
                        </tr>
                        <tr> <td>------------------------------</td> </tr>
                        <tr>
                            <td> <input type="radio" name='mod' value='dodajkorisnika'> Dodaj korisnika </input> </td>
                        </tr>
                        <tr>
                            <td> <input type="radio" name='mod' value='dodajhranu'> Dodaj namirnicu </input> </td>
                        </tr>
                        <tr> <td>------------------------------</td> </tr>
                     </form>


                </table>

            </div>
        </div>
        <?php }?>

        <div id='main'>
            
            
            <?php if ($_smarty_tpl->tpl_vars['trenmod']->value=='1'){?>
            <div class='polje'>
                <h1>Dodaj korisnika</h1>
                <br/>
                <br/>
                <form method='POST' action="./index.php">
                    <table>
                        <form method='POST' action="./index.php">
                        <tr>
                            <td>--------------------------------------------</td>
                        </tr>
						</form>
                        <form method='POST' action="./index.php">
                        <tr>
                            <td> <button> Promeni opciju </button> </td>
                            <input type="hidden" name='mod' value='nijedan' />
                            <input type="hidden" name='debug' value='true' />
                        </tr>
                    </table>
                </form>
                    </table>
                </form>
            </div>
            <?php }elseif($_smarty_tpl->tpl_vars['trenmod']->value=='2'){?>
            <div class='polje' >
                <h1>Dodaj namirnicu</h1>
                <br/>
                <br/>
                <form method='POST' action="./index.php">
                    <table>
                        <tr>
                            <td>Naziv</td>
                            <td> <input type="text" name="naziv" class="txtPoljeZaHranu" /> </td>
                        </tr>
                        <tr>
                            <td>Kalorije</td>
                            <td> <input type="text" name="kalorije" class="txtPoljeZaHranu"/> </td>
                        </tr>
                        <tr>
                            <td>Proteini</td>
                            <td> <input type="text" name="proteini" class="txtPoljeZaHranu"/> </td>
                        </tr>
                        <tr>
                            <td>Masti</td>
                            <td> <input type="text" name="masti" class="txtPoljeZaHranu"/> </td>
                        </tr>
                        <tr>
                            <td>Ugljeni hidrati</td>
                            <td> <input type="text" name="ugh" class="txtPoljeZaHranu"/> </td>
                        </tr>
                        <tr>
                            <td> <button> Potvrdi </button> </td>
                            <input type="hidden" name='mod' value='dodajhranu' />
                            <input type="hidden" name='debug' value='false' />
                        </tr>
                        <tr>
                            <td>--------------------------------------------</td>
                        </tr>
                </form>
                <form method='POST' action="./index.php">
                        <tr>
                            <td> <button> Promeni opciju </button> </td>
                            <input type="hidden" name='mod' value='nijedan' />
                            <input type="hidden" name='debug' value='true' />
                        </tr>
                    </table>
                </form>
            </div>
            <?php }?>
            
            
        </div>
        
 
    
    </body>
    
</html><?php }} ?>