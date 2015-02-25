<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Collections\Collection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Actor;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Movie;

/**
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class ActorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException BadMethodCallException
     */
    public function testActorGetMovieToManyArguments()
    {
        $actor = new Actor();
        $actor->getMovies(1);
    }

    public function testActorRemoveMovieEmpty()
    {
        $actor = new Actor();
        $movie = new Movie();
        $this->assertEmpty($actor->getMovies());
        $actor->removeMovie($movie);
        $this->assertEmpty($actor->getMovies());
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testActorAddMovieToManyArguments()
    {
        $actor = new Actor();
        $movie = new Movie();
        $actor->addMovie($movie, 2);
    }

    public function testActorAddMovie()
    {
        $actor = new Actor();
        $movie = new Movie();
        $actor->addMovie($movie);
        $actor->addMovie($movie);
        $this->assertSame($movie, $actor->getMovies()->first());
    }

    public function testActorRemoveMovie()
    {
        $actor = new Actor();
        $movie = new Movie();

        $this->assertEmpty($actor->getMovies());
        $actor->removeMovie($movie);
        $this->assertEmpty($actor->getMovies());

        $actor->addMovie($movie);
        $this->assertSame($movie, $actor->getMovies()->first());

        $actor->removeMovie($movie);
        $this->assertEmpty($actor->getMovies());
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testActorRemoveMovieToManyArguments()
    {
        $actor = new Actor();
        $movie = new Movie();
        $actor->removeMovie($movie, 2);
    }

    public function testActorMovie()
    {
        // Create object for many-to-many bi-directional association
        $actor = new Actor();
        $movie = new Movie();

        // The collections should be emptye
        $this->assertEmpty($movie->getActors());

        // The collections should subclass Collection
        $this->assertInstanceOf(Collection::class, $actor->getMovies());
        $this->assertInstanceOf(Collection::class, $movie->getActors());

        // Add actor to movie and retrieve it again from the other side
        $movie->addActor($actor);
        $this->assertSame($movie, $actor->getMovies()->first());
        $this->assertSame($actor, $movie->getActors()->first());

        // Remove again
        $movie->removeActor($actor);
        $this->assertEmpty($actor->getMovies());
        $this->assertEmpty($movie->getActors());

        // Retrieve empty collection and fill afterwards,
        // the previous received object should contain the
        // new values;
        $movies = $actor->getMovies();
        $actors = $movie->getActors();
        $actor->addMovie($movie);
        $this->assertSame($movie, $movies->first());
        $this->assertSame($actor, $actors->first());
    }
}
