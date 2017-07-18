<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("MasterPage.php");
require_once("MySQLHandler.php");


class CategoryLatexView extends MasterPage
{
    private $mCategory;
    private $mCategoryId;

    public function __construct()
    {
        parent::__construct();

        $this->mCategory=$_GET['category'];
        $this->mCategoryId=$_GET['category_id'];

        $this->setTitle("Latex View: ".ucfirst($this->mCategory));

        $this->setBGColor('white');
        $this->setFGColor('black');
    }

    public function render_body()
    {
        $content="";
        
        $handler=new MySQLHandler();
        $handler->connect();
        $handler->select_database();

        $sQuery="SELECT * FROM mc".$this->mCategory."dictionary WHERE id='".$this->mCategoryId."'";
        $rResult=mysql_query($sQuery) or die(mysql_error());
        $rRow=mysql_fetch_array($rResult);

        if(isset($rRow))
        {
            $desc=htmlspecialchars_decode($rRow['description']);
        }
        else
        {
            $desc="";
        }

        $handler->disconnect();

        $content.="<div style='color:black;background-color:white'>";
        $content.=$this->latex_decode($desc);
        $content.='</div>';

        echo $content;
    }

    private function latex_decode($desc)
    {
        $content='';
        $content=str_replace('<ol>', '<br />\begin{enumerate}<br />', $desc);
        $content=str_replace('</ol>', '\end{enumerate}<br />', $content);
        $content=str_replace('<ul>', '<br />\begin{itemize}<br />', $content);
        $content=str_replace('</ul>', '<br />\end{itemize}<br />', $content);
        $content=str_replace('<li>', '\item<br/>', $content);
        $content=str_replace('</li>', '<br />', $content);
        $content=str_replace('<table>', '<br />\begin{framed}<br />\begin{flushleft}<br />', $content);
        $content=str_replace('</table>', '<br />\end{flushleft}<br />\end{framed}<br />', $content);
        $content=str_replace('<tr>', '', $content);
        $content=str_replace('<td>', '', $content);
        $content=str_replace('</td>', '', $content);
        $content=str_replace('</tr>', '', $content);

        $greeks=Array('alpha',
                    'beta',
                    'gamma',
                    'delta',
                    'epsilon',
                    'varepsilon',
                    'zeta',
                    'eta',
                    'theta',
                    'vartheta',
                    'iota',
                    'kappa',
                    'lambda',
                    'mu',
                    'nu',
                    'xi',
                    'pi',
                    'varpi',
                    'rho',
                    'varrho',
                    'sigma',
                    'varsigma',
                    'tau',
                    'upsilon',
                    'phi',
                    'varphi',
                    'chi',
                    'psi',
                    'omega',
                    'Gamma',
                    'Lambda',
                    'Sigma',
                    'Psi',
                    'Delta',
                    'Xi',
                    'Upsilon',
                    'Omega',
                    'Theta',
                    'Pi',
                    'Phi'
        );

        $symbols=Array(
                    'infty',
                    'in',
                    'notin',
                    'forall',
                    'exists',
                    'notexists',
                    'partial',
                    'approx',
                    'pm',
                    'inter',
                    'sqrt'
        );

        $latex_code=Array();
        if(preg_match_all('/<m> .*? <\/m>/isx', $content, $matches))
        {

            foreach($matches[0] as $match)
            {
                //echo 'before: '.$match.'<br />';
                $lv=$match;

                $lv=preg_replace('/sum{(.*?)}{(.*?)}{(.*?)}/', '\sum_{$1}^{$2}{$3}', $lv);
                $lv=preg_replace('/prod{(.*?)}{(.*?)}{(.*?)}/', '\prod_{$1}^{$2}{$3}', $lv);
                $lv=preg_replace('/delim{lbrace}{(.*?)}{rbrace}/', '\{$1\}', $lv);
                $lv=preg_replace('/delim{[}{(.*?)}{]}/', '[$1]', $lv);
                $lv=preg_replace('/delim{\|}{(.*?)}{\|}/', '|$1|', $lv);

                $lv=str_replace('_{}', '', $lv);
                $lv=str_replace('^{}', '', $lv);

                /*
                if(preg_match_all('/sum{(.*?)}{(.*?)}{(.*?)}/', $lv, $matches_sum))
                {
                    foreach($matches_sum[0] as $match_sum)
                    {
                        echo $match_sum.'<br />';
                    }
                }
                */
                foreach($greeks as $value)
                {
                    $lv=str_replace(' '.$value, ' \\'.$value, $lv);
                    $lv=str_replace('{'.$value, '{\\'.$value, $lv);
                    $lv=str_replace('('.$value, '(\\'.$value, $lv);
                    $lv=str_replace('<m>'.$value, '<m>\\'.$value, $lv);
                }

                foreach($symbols as $value)
                {
                    $lv=str_replace(' '.$value, ' \\'.$value, $lv);
                    $lv=str_replace('{'.$value, '{\\'.$value, $lv);
                    $lv=str_replace('('.$value, '(\\'.$value, $lv);
                    $lv=str_replace('<m>'.$value, '<m>\\'.$value, $lv);
                }

                $latex_code[$match]=$lv;
                
                //echo 'after: '.$lv.'<br />';
            }
        }

        foreach($latex_code as $match => $lv)
        {
            $content=str_replace($match, '$'.$lv.'$', $content);
        }
        
        return $content;
    }
}

$page=new CategoryLatexView();
$page->render();

?>
