<?php

namespace Framework\Server\WebSocket;

class WebSocketServer implements \Framework\Server\Interfaces\IServer
{
    protected $socket;
    protected int $error_code = 0;
    protected string $error_message = '';

    public function __construct(
        protected string $url,
        protected int $workers_count = 10
    )
    {
        $this->socket = \stream_socket_server($this->url, $this->error_code, $this->error_message);
        if (!$this->socket) {
            throw new \Exception("{$this->error_message} ($this->error_code)");
        }
    }


    public function start(): void
    {
        list($pid, $master, $workers) = $this->spawnWorkers();

        if ($pid) {//мастер
            fclose($this->socket);//мастер не будет обрабатывать входящие соединения на основном сокете
            $WebsocketMaster = new WebsocketMaster($workers);//мастер будет пересылать сообщения между воркерами
            $WebsocketMaster->start();
        } else {//воркер
            $WebsocketHandler = new WebSocketHandler($this->socket, $master);
            $WebsocketHandler->start();
        }
    }

    protected function spawnWorkers(): array
    {
        $master = null;
        $workers = [];

        for($i = 0; $i < $this->workers_count; $i++)
        {
            $pair = \stream_socket_pair(STREAM_PF_INET, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
            $pid = \pcntl_fork();

            if($pid == -1) { die("error: pcntl_fork\r\n"); }
            elseif($pid)
            {
                fclose($pair[0]);
                $workers[$pid] = $pair[1];
            }
            else
            {
                fclose($pair[1]);
                $master = $pair[0];
                break;
            }
        }

        return [$pid, $master, $workers];
    }
}