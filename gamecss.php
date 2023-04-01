<?php
use SplObserver;
use SplSubject;

class Game {
	private $nest = array();
    public function __construct() {
        $queen = new Queen();
        for( $i = 0; $i < 5; $i++) {
            $this->nest[] = new Worker();
        }
        for($i = 0; $i < 8; $i++) {
            $this->nest[] = new Drone();
        }
        foreach($this->nest as $wasp) {
            $queen->attach($wasp);
        }
        $this->nest[] = $queen;
    }

    public function loadWaspAsset($isAlive, $asset, $points) {
        return ($isAlive) ? "<img class='wasp-style ' src=".$asset." /><span class='hitpoint'>".$points."</span>" : 
        "<img class='wasp-style imgfade' src='worker.jpg' />"; 
    }

    public function show() {
        echo '<div id="main">';
        if ($this->getAliveWasps() == null) { echo '<h2 class="game-over">Game Over</h2>'; } 
        else { echo '<h2 class="game-text"> WASP\'S HIT </h2>'; }
        echo '<div class="board">';       
                foreach ($this->nest as $wasp){
                    echo $this->loadWaspAsset($wasp->isAlive(), 'assets/'.$wasp->type.'.jpg', $wasp->hitPts); 
                }
        echo '</div><br>';
       
        if ($this->getAliveWasps() == null) {
            echo    '<form action="gamecss.php" method="post">
                        <input class="begin-btn" type="submit" value="Begin New Game" name="new">
                    </form>';
            session_destroy();
        }
        else {
            echo '<form class="" action="gamecss.php" method="post">
                    <input class="game-btn" type="submit" value="Hit Wasp" name="hit">
                </form>';
        }
        echo "</div>";
    }

    private function getAliveWasps() {
        return array_filter($this->nest, function ($wasp) { return $wasp->isAlive(); });
    }

    public function hitRandomWasp() {
        $aliveWasps = $this->getAliveWasps();
        $randomWaspIdx = array_rand($aliveWasps);
        $aliveWasps[$randomWaspIdx]->hit();
    }
}

abstract class Wasp implements SplObserver {
    public $type;
    public $hitPts;
    protected $lostPts;

    public function __construct($type, $hitPts, $lostPts) {
        $this->type = $type;
        $this->hitPts = $hitPts;
        $this->lostPts = $lostPts;
    }

    public function hit() {
        $this->hitPts -= $this->lostPts;
        if ($this->hitPts <= 0) $this->kill();
    }

    public function kill() {
        $this->hitPts = 0;
    }

    public function isAlive() {
        return ($this->hitPts != 0);
    }

    public function update(SplSubject $queen) {
        if (!$queen->isAlive()) $this->kill();
    }
}

class Queen extends Wasp implements SplSubject {
    private $observers;

    public function __construct() {
        parent::__construct("Queen", 80, 7);
        $this->observers = new SplObjectStorage();
    }

    public function attach(SplObserver $observer) {
        $this->observers->attach($observer);
    }

    public function detach(SplObserver $observer) {
        $this->observers->detach($observer);
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function hit() {
        parent::hit();
        $this->notify();
    }
}

class Worker extends Wasp {
    public function __construct() {
        parent::__construct("Worker", 68, 10);
    }
}

class Drone extends Wasp {
    public function __construct() {
        parent::__construct("Drone", 60, 12);
    }
}

session_start();

if (!isset($_SESSION["game"]) || isset($_POST["new"]))
    $_SESSION["game"] = new Game();
if (isset($_POST["hit"])) $_SESSION["game"]->hitRandomWasp();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Wasp Game</title>
        <style>
            #main {
                width: 480px;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                text-align: center;
                background-color: #EEEEEE;
                border-radius: 40px;
                height: 310px;
            }
            .board {
                height: 180px;
                border: 2px solid #38A5F9;
                border-radius: 8px;
                padding: 10px;
                margin: 0 50px;
                background-color: #38A5F9;
            }
            .wasp-style {
                width: 50px; 
                height: 50px;
                margin: 2px;
                border: 1px dotted #333333;
            }
            .hitpoint {
                background-color: #CCAA37;
                color: #ffffff; 
                border-radius: 50%;
                padding: 3px;
                margin-left: -7px;
            }
            .imgfade { opacity: 0.3; border: none; }
            .game-btn {
                width: 150px;
                color: #ffffff;
                background-color: #0C3170;
                border: 5px solid #38A5F9;
                border-radius: 20px;
                padding: 10px;
                cursor: pointer;
            }
            .begin-btn {
                width: 150px;
                color: #ffffff;
                background-color: #AF0000;
                border: 5px solid #FF2525;
                border-radius: 20px;
                padding: 10px;
                cursor: pointer;
            }
            .game-over { color: #af0000; text-decoration: line-through; }
            .game-text { color: #0C3170; font-weight: 500; text-decoration: underline; }
        </style>
    </head>
    <body>
        <?php $_SESSION["game"]->show(); ?>
    </body>
</html>
