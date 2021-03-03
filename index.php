<?php 

ini_set('log_errors', 'on');
ini_set('error_log', 'php.log');
session_start();

//クイズオブジェクト
$questions = array();

//抽象クラス(基礎部分設計)
abstract class Foundation{
    protected $img;
    protected $answer;
    protected $choice1;
    protected $choice2;
    protected $choice3;
    abstract public function setQ();
    public function setImg($str){
        $this->img = $str;
    }
    public function getImg(){
        return $this->img;
    }
    public function setAnswer($str){
        $this->answer = $str;
    }
    public function getAnswer(){
        return $this->answer;
    }
    public function setChoice1($str){
        $this->choice1 = $str;
    }
    public function getChoice1(){
        return $this->choice1;
    }
    public function setChoice2($str){
        $this->choice2 = $str;
    }
    public function getChoice2(){
        return $this->choice2;
    }
    public function setChoice3($str){
        $this->choice3 = $str;
    }
    public function getChoice3(){
        return $this->choice3;
    }
}

//建物問題クラス
class building extends Foundation{
    private $question;
    public function __construct($img, $answer, $choice1, $choice2, $choice3){
        $this->img = $img;
        $this->answer = $answer;
        $this->choice1 = $choice1;        $this->choice2 = $choice2;
        $this->choice3 = $choice3;
    }
    public function setQ(){
        $qSentence = 'この建物の名前は？？';
        return $qSentence;
    }
}

//街並み問題クラス
class landscape extends Foundation{
    public function __construct($img, $answer, $choice1, $choice2, $choice3){
        $this->img = $img;
        $this->answer = $answer;
        $this->choice1 = $choice1;        $this->choice2 = $choice2;
        $this->choice3 = $choice3;
    }
    public function setQ(){
        $qSentence = 'この景色はどこ？？';
        return $qSentence;
    }
}
interface HistoryInterface{
    public static function count();
    public static function clear();
}
//履歴管理クラス
class History implements HistoryInterface{
    public static function count(){
        if(empty($_SESSION['count'])) $_SESSION['count'] = '';
        $_SESSION['count']++;
    }
    public static function getCount(){
        if(isset($_SESSION['count'])){
        return $_SESSION['count'];
        }
    }
    public static function clear(){
        unset($_SESSION['count']);
        $_SESSION['count'] = 0;
    }
}

//インスタンス生成
$questions[] = new landscape('travelimg/pic1.JPG', '生石高原', '生石高原', 'ハチ北高原', '秋吉台');
$questions[] = new landscape('travelimg/pic2.jpeg', 'ローマ', 'アテネ', 'アムステルダム', 'ローマ');
$questions[] = new landscape('travelimg/pic3.jpg', 'ブラーノ島', '志摩スペイン村', 'ニューヨーク郊外', 'ブラーノ島');
$questions[] = new building('travelimg/pic4.jpg', 'サン・ジョルジョ・マッジョーレ教会', 'サン・ジョルジョ・マッジョーレ教会', 'ミラノ大聖堂', '聖ヴィート大聖堂');
$questions[] = new landscape('travelimg/pic5.jpg', 'チェコ', 'ハンガリー', 'オーストリア', 'チェコ');
$questions[] = new building('travelimg/pic6.jpg', 'エッフェル塔', '東京タワー', 'エッフェル塔', 'ピサの斜塔');
$questions[] = new landscape('travelimg/pic7.jpg', 'ロンドン', 'ベルリン', 'ロンドン', 'パリ');
$questions[] = new landscape('travelimg/pic8.jpg', 'ヴェネツィア', 'ヴェネツィア', 'ドバイ', 'イスタンブール');

function createQuestion(){
    global $questions;
    $question = $questions[mt_rand(0,7)];
    $_SESSION['question'] = $question;
}

function init(){
    History::clear();
    History::getCount();
    createQuestion();
    $_SESSION['msg'] = '';
}

function gameOver(){
    $_SESSION = array();
};
function gameOverMsg(){
    $_SESSION['msg'] = 'Game Over';
}

//画面処理

if(!empty($_POST)){
    print_r($_SESSION,true);
    $choiceFlg = ( !empty($_POST['choice1']) || !empty($_POST['choice2']) || !empty($_POST['choice3']) ) ? true :false;
    $startFlg = (!empty($_POST['start'])) ? true : false;
    if($startFlg){
        init();
    }else{
        if($choiceFlg){
            //postされたchoiceが正解かどうかの判定
            //正解の場合、新しいquizzer生成
            if(!empty($_POST['choice1'])){$choice = $_POST['choice1'];}
            if(!empty($_POST['choice2'])){$choice = $_POST['choice2'];}
            if(!empty($_POST['choice3'])){$choice = $_POST['choice3'];}
            print_r($choice, true);
            
            //ここのquestionに入ってないって出るけど、入ってない場合ここの処理にこないはずやのになんでなん？
            if($choice === $_SESSION['question']->getAnswer()){
                History::count();
                $_SESSION['msg'] = '正解';
                createQuestion();
            }else{
            gameOverMsg();
            gameOver();
            }
        }
    }
    $_POST = array();
}


?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>建物・街並みクイズ</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="app.js"></script>
</head>
<body>

    <div class="js-show-msg msg-panel" style="display: none;">
        <p id="js-show-msg"><?php if(!empty($_SESSION['msg'])) echo $_SESSION['msg']; ?></p>
    </div>

    <?php if(empty($_SESSION)){ ?>
        <div class="main-area">
        <div class="title-area">
            <h1 class="title">建物・街並みクイズ</h1>
        </div>
        <form action="" method="post">
            <input type="submit" name="start" value="ゲームスタート">
        </form>
        
    </div>
    <?php }else{ ?>
    <div class="main-area">
        <div class="img-area">
            <img src="<?php echo $_SESSION['question']->getImg(); ?>" alt="">
        </div>
        <div class="question-area">
            <h2><?php echo $_SESSION['question']->setQ(); ?></h2>
        </div>
        <div class="choice-area">
            <form name="form" action="" method="POST">
                <input type="submit" name="choice1" value="<?php echo $_SESSION['question']->getChoice1(); ?>" class="choice">
                <input type="submit" name="choice2" value="<?php echo $_SESSION['question']->getChoice2(); ?>" class="choice">
                <input type="submit" name="choice3" value="<?php echo $_SESSION['question']->getChoice3(); ?>" class="choice">
            </form>
    <?php } ?>
        </div>
    </div>
    <div class="history-area">
        <p>正解数：<span><?php echo History::getCount(); ?></span></p>
    </div>
</body>
</html>