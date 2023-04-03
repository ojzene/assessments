<?php

include_once("Game.php");

use PHPUnit\Framework\TestCase;

final class GameTest extends TestCase {

    public function testClassConstructor() {
        $game = new Game(); 

        $this->assertNotNull($game->getAliveWasps());
    }

    public function testQueen() {
        $queen = new Queen();
        
        $this->assertSame("Queen", $queen->type);
        $this->assertTrue($queen->isAlive());
    }

    public function testWorker() {
        $worker = new Worker();

        $this->assertSame("Worker", $worker->type);
        $this->assertTrue($worker->isAlive());
    }

    public function testDrone() {
        $drone = new Drone();
        
        $this->assertSame("Drone", $drone->type);
        $this->assertTrue($drone->isAlive());
    }

}
