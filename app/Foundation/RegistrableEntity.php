<?php

namespace PluginClassName\Foundation;

abstract class RegistrableEntity
{
    /**
     * Hook alla registrazione (init di default)
     */
    public function boot(string $hook = 'init', int $priority = 10): void
    {
        add_action($hook, function () {
            if ($this->shouldRegister()) {
                $this->register();
            }
        }, $priority);
    }

    /**
     * Verifica se deve essere registrato (override se necessario)
     */
    public function shouldRegister(): bool
    {
        return true;
    }

    /**
     * Slug dell'entità
     */
    abstract public function getSlug(): string;

    /**
     * Argomenti di registrazione
     */
    abstract public function toArray(): array;

    /**
     * Metodo effettivo di registrazione (da implementare)
     */
    abstract public function register(): void;
}