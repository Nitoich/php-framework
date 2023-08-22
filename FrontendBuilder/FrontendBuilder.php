<?php

namespace Framework\FrontendBuilder;

class FrontendBuilder
{
    protected HTMLElement $root;
    public function __construct(HTMLElement $element, string $root_selector = 'div')
    {
        $this->root = new HTMLElement("", $root_selector);
        $this->root->add($element);
    }

    public function make(): string
    {
        return $this->root->getChildren()[0]->make();
    }
}