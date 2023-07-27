<?php

class RouteSegment {
    protected $segment;
    function __construct(string $segment)
    {
        $this->segment = $segment;
    }

    function isParam(): bool
    {
        if (strlen($this->segment) < 2) {
            return false;
        }
        if ($this->segment[0] == ':') {
            return true;
        }
        return false;
    }
}

class Route {

    protected $verb, $uri, $uri_segments;

    function __construct($verb, $uri)
    {
        $this->verb = $verb;
        $this->uri = $uri;
        $this->uri_segments = $this->getUriSegments();
    }

    /**
     * @return bool Whether the URL matches the current route
     */
    public function matches(string $url): bool
    {
        if ($this->countSegments($url) !== count($this->getUriSegments())) {
            return false;
        }
        return true;
    }

    /**
     * @return RouteSegment[] The number of segments in the URI
     */
    public function getUriSegments()
    {
        $uri = trim($this->uri, '/'); // remove start and end slashes
        return array_map(function ($s) { return new RouteSegment($s); }, explode('/', $uri));
    }

    /**
     * @param string $url
     *
     * @return string[]
     */
    public function parse($url): array
    {
        $uri = $this->getSegments($this->uri);
        $url = is_array($url) ? $url : $this->getSegments($url);
        $result = [];
        foreach ($url as $key => $value) {
            if (isset($uri[$key]) && $uri[$key] !== $value) {
                $result[] = $value;
            }
        }
        return $result;
    }

    protected function getSegments($uri): array
    {
        $uri = trim($uri, '/'); // remove start and end slashes
        return explode('/', $uri);
    }

    protected function countSegments($uri): int
    {
        return count($this->getSegments($uri));
    }

    /**
     * @return RouteSegment[]
     */
    protected function paramSegments(): array
    {
        return array_filter($this->uri_segments, function ($s) { return $s->isParam(); });
    }

    function hasNamedParam()
    {

    }

    function isIndex(): bool
    {
        if (count($this->paramSegments()) === 0) {
            return true;
        }
        return false;
    }
}


if (isset($argc) && $argc > 0) {

    $r = new Route('GET', '/pages/:id');
    assert(count($r->getUriSegments()) == 2);
    assert($r->matches('/pages/1'));
    assert(!$r->isIndex());

    $r = new Route('GET', '/pages');
    assert($r->isIndex());

}
