<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Collections\Collection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Actor;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Movie;
use PHPUnit\Framework\TestCase;

class ActorTest extends TestCase
{
    public function testActorGetMovieToManyArguments(): void
    {
        $actor = new Actor();

        $this->expectException(\BadMethodCallException::class);

        $actor->getMovies(1);
    }

    public function testActorRemoveMovieEmpty(): void
    {
        $actor = new Actor();
        $movie = new Movie();
        self::assertEmpty($actor->getMovies());
        $actor->removeMovie($movie);
        self::assertEmpty($actor->getMovies());
    }

    public function testActorAddMovieToManyArguments(): void
    {
        $actor = new Actor();
        $movie = new Movie();

        $this->expectException(\BadMethodCallException::class);

        $actor->addMovie($movie, 2);
    }

    public function testActorAddMovie(): void
    {
        $actor = new Actor();
        $movie = new Movie();
        $actor->addMovie($movie);
        $actor->addMovie($movie);
        self::assertSame($movie, $actor->getMovies()->first());
    }

    public function testActorRemoveMovie(): void
    {
        $actor = new Actor();
        $movie = new Movie();

        self::assertEmpty($actor->getMovies());
        $actor->removeMovie($movie);
        self::assertEmpty($actor->getMovies());

        $actor->addMovie($movie);
        self::assertSame($movie, $actor->getMovies()->first());

        $actor->removeMovie($movie);
        self::assertEmpty($actor->getMovies());
    }

    public function testActorRemoveMovieToManyArguments(): void
    {
        $actor = new Actor();
        $movie = new Movie();

        $this->expectException(\BadMethodCallException::class);

        $actor->removeMovie($movie, 2);
    }

    public function testActorMovie(): void
    {
        // Create object for many-to-many bi-directional association
        $actor = new Actor();
        $movie = new Movie();

        // The collections should be emptye
        self::assertEmpty($movie->getActors());

        // The collections should subclass Collection
        self::assertInstanceOf(Collection::class, $actor->getMovies());
        self::assertInstanceOf(Collection::class, $movie->getActors());

        // Add actor to movie and retrieve it again from the other side
        $movie->addActor($actor);
        self::assertSame($movie, $actor->getMovies()->first());
        self::assertSame($actor, $movie->getActors()->first());

        // Remove again
        $movie->removeActor($actor);
        self::assertEmpty($actor->getMovies());
        self::assertEmpty($movie->getActors());

        // Retrieve empty collection and fill afterwards,
        // the previous received object should contain the
        // new values;
        $movies = $actor->getMovies();
        $actors = $movie->getActors();
        $actor->addMovie($movie);
        self::assertSame($movie, $movies->first());
        self::assertSame($actor, $actors->first());
    }
}
