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

    public function show() {
        echo '<ul>';        
        foreach ($this->nest as $wasp){
            echo "<li style=\"color:", ($wasp->isAlive()) ? "black" : "red; text-decoration: line-through;" , "\">{$wasp->type} ({$wasp->hitPts})</li>";
        }
        echo '</ul>';
        if ($this->getAliveWasps() == null) {
            echo '<h1>Game Over</h1>
                    <form action="game.php" method="post">
                        <input type="submit" value="Begin New Game" name="new">
                    </form>';
            session_destroy();
        }
        else {
            echo '<form class="" action="game.php" method="post">
                    <input type="submit" value="Hit Wasp" name="hit">
                </form>';
        }
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
    <head><title>Wasp Game</title></head>
    <body>
        <?php $_SESSION["game"]->show(); ?>
    </body>
</html>
