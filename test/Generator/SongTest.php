<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Collections\Collection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Genre;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Song;

class SongTest extends \PHPUnit_Framework_TestCase
{
    public function testGetGenres()
    {
        $song   = new Song();
        $genres = $song->getGenres();
        $this->assertEmpty($genres);
        $this->assertInstanceOf(Collection::class, $genres);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetGenresTooManyArguments()
    {
        $song = new Song();
        $song->getGenres(1);
    }

    /**
     * @depends testGetGenres
     */
    public function testAddGenre()
    {
        $radar_love = new Song();
        $help       = new Song();

        $rock = new Genre();
        $jazz = new Genre();

        // Add and receive a genre
        $radar_love->addGenre($rock);
        $this->assertSame($rock, $radar_love->getGenres()->first());
        $this->assertCount(1, $radar_love->getGenres());

        // Test if we got a reference
        $genres = $radar_love->getGenres();

        // Add the same genre again, we expect no error
        // but also no duplicate entries.
        $radar_love->addGenre($rock);
        $this->assertSame($rock, $genres->first());
        $this->assertCount(1, $genres);

        // Add the same genre again, we expect no error
        // but also no duplicate entries.
        $radar_love->addGenre($jazz);
        $this->assertSame($jazz, $genres->last());
        $this->assertCount(2, $genres);

        // Add same genres to multiple songs
        $help->addGenre($rock);
        $help->addGenre($jazz);
        $this->assertEquals([$rock, $jazz], $help->getGenres()->toArray());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testAddGenreTooManyArguments()
    {
        $song  = new Song();
        $genre = new Genre();
        $song->addGenre($genre, 2);
    }

    /**
     * @depends testGetGenres
     * @depends testAddGenre
     */
    public function testRemoveGenre()
    {
        $song  = new Song();
        $genre = new Genre();

        // The initial list should be empty.
        $this->assertEmpty($song->getGenres());

        // Add and receive a genre.
        $song->addGenre($genre);
        $this->assertSame($genre, $song->getGenres()->first());
        $this->assertEquals(1, $song->getGenres()->count());

        // Remove genre, check return value and check list.
        $this->assertSame($song->removeGenre($genre), $song);
        $this->assertEquals(0, $song->getGenres()->count());

        // Remove not existing genre, check return value. No
        // error is expected.
        $this->assertSame($song->removeGenre($genre), $song);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testRemoveGenreTooManyArguments()
    {
        $song  = new Song();
        $genre = new Genre();
        $song->removeGenre($genre, 2);
    }
}
