<?php

namespace Framework\FrontendBuilder;

class HTMLElement
{
    protected array $children = [];
    protected array $attributes = [];
    protected array $styles = [];
    protected array $classList = [];
    protected string $content = "";
    protected array $event_listeners = [];
    protected string $selector = 'div';

    public function __construct(string $content = "", string $selector = 'div')
    {
        $this->content = $content;
        $this->selector = $selector;
    }

    /**
     * @return array<HTMLElement>
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function add(HTMLElement $element): void
    {
        $this->children[] = $element;
    }
    public function setEventListener(string $event, string $JSPath): void
    {
        $this->event_listeners[$event] = $JSPath;
    }

    public function make(): string
    {
        $html = "<{$this->selector}";
        foreach ($this->attributes as $attribute => $value)
            $html .= "$attribute=\"$value\"";

        $html .= ">";
        $html .= $this->content;
        foreach ($this->children as $child)
            $html .= $child->make();

        $html .= "</{$this->selector}>";

        return $html;
    }
}