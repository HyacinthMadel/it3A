<?php

function redirectTo(string $url): void
{
    header('Location: ' . $url);
    exit;
}
