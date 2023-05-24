<?php

namespace Framework\Server\WebSocket;

class WebSocketMaster
{
    protected array $clients = [];

    public function __construct(
        protected array $workers = []
    ) {}

    public function start(): void
    {
        while(true)
        {
            $read = $this->clients;

            stream_select($read, $write, $except, null);

            if($read)
            {
                foreach ($read as $client)
                {
                    $data = fread($client, 1000);

                    if(!$data)
                    {
                        unset($this->clients[intval($client)]);
                        @fclose($client);
                        continue;
                    }

                    foreach ($this->workers as $worker)
                    {
                        if($worker !== $client)
                        {
                            fwrite($worker, $data);
                        }
                    }
                }
            }
        }
    }
}