<?php

namespace Catalog;

class MultiRequest
{
    private $multiDescriptor;
    private $descriptors = [];
    private $subrequests;

    public function call($method, $url, array $data = [])
    {
        return new Subrequest($method, $url, $data);
    }

    public function get($url, array $data = [])
    {
        return $this->call('GET', $url, $data);
    }

    public function post($url, array $data = [])
    {
        return $this->call('POST', $url, $data);
    }

    public function put($url, array $data = [])
    {
        return $this->call('PUT', $url, $data);
    }

    public function patch($url, array $data = [])
    {
        return $this->call('PATCH', $url, $data);
    }

    public function delete($url, array $data = [])
    {
        return $this->call('DELETE', $url, $data);
    }

    public function options($url, array $data = [])
    {
        return $this->call('OPTIONS', $url, $data);
    }

    public function head($url, array $data = [])
    {
        return $this->call('HEAD', $url, $data);
    }

    public function send(array $subrequests)
    {
        $this->subrequests = $subrequests;
        $this->createSubrequests();
        $this->execSubrequests();
        return $this->getResults();
    }

    private function createSubrequests()
    {
        $this->multiDescriptor = curl_multi_init();
        foreach ($this->subrequests as $request) {
            $descriptor = $request->create();
            curl_multi_add_handle($this->multiDescriptor, $descriptor);
            $this->descriptors[] = $descriptor;
        }
    }

    private function execSubrequests()
    {
        do {
            curl_multi_exec($this->multiDescriptor, $active);
            curl_multi_select($this->multiDescriptor);
        } while ($active > 0);
    }

    private function getResults()
    {
        $result = [];
        foreach ($this->subrequests as $name=>$request)
        {
            $result[$name] = $request->getResult();
            curl_multi_remove_handle($this->multiDescriptor, $request->getDescriptor());
        }
        curl_multi_close($this->multiDescriptor);
        return $result;
    }

    public function getLastExecutionTime()
    {

    }
}