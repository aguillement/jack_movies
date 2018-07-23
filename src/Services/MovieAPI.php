<?php

namespace App\Services;


use GuzzleHttp\Client;

/**
 * Class MovieAPI
 * @package App\Services
 */
class MovieAPI
{
    private $url = 'http://api.themoviedb.org/3/';
    private $proxy = 'proxy-sh.ad.campus-eni.fr:8080';
    private $api_key = 'bfff8381b65e5601a54e534afd05b540';
    private $client;

    /**
     * MovieAPI constructor.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $search
     * @return \Exception|mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function searchMovie(string $search)
    {
        try {
            $res = $this->client->request('POST', $this->url.'search/movie', [
                'form_params' => [
                    'query' => $search,
                    'api_key' => $this->api_key,
                ],
                'curl' => [
                    CURLOPT_PROXY => $this->proxy,
                ],
            ]);

            $res = json_decode($res->getBody()->getContents())->{'results'};
            $res = $this->formatMovie($res);

            return $res;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param $movies
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function formatMovie($movies)
    {
        $moviesList = [];

        foreach ($movies as $movie) {
            $res = $this->client->request('GET', $this->url.movie/'.$movie->{'id'}.'?append_to_response=credits,videos,similar', [
                'form_params' => [
                    'api_key' => $this->api_key,
                ],
                'curl' => [
                    CURLOPT_PROXY => $this->proxy,
                ],
            ]);
            $res = json_decode($res->getBody()->getContents());

            $official_website = $res->{'homepage'};

            //TODO remove default value, set default value in database (entity)
            // Video
            $video = $res->{'videos'}->{'results'}[0] ?? null;
            $video_name = $video->{'name'} ?? null;
            $video_key = $video->{'key'} ?? null;

            $director = $res->{'credits'}->{'crew'}[0]->{'name'} ?? 'X';
            $duration = $res->{'runtime'} ?? 0;
            $releaseDate = $res->{'release_date'} ?? '2000-01-01';
            $synopsis = $res->{'overview'} ?? 'overview';
            $picture = $res->{'poster_path'} ?? null;

            $vote_average = $res->{'vote_average'} ?? null;
            $vote_count = $res->{'vote_count'} ?? null;

            $genres = [];
            foreach ($res->{'genres'} as $genre) {
                $genres[] = $genre->{'name'};
            }

            $movie = [
                'title' => $movie->{'title'},
                'director' => $director,
                'duration' => $duration,
                'releaseDate' => $releaseDate,
                'synopsis' => $synopsis,
                'picture' => $picture,
                'category' => $genres,
                'video_key' => $video_key,
                'video_name' => $video_name,
                'vote_average' => $vote_average,
                'vote_count' => $vote_count,
                'official_website' => $official_website,
            ];
            $moviesList[] = $movie;
        }

        return json_encode($moviesList);
    }
}
