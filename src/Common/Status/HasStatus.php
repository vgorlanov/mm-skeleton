<?php

namespace Common\Status;

interface HasStatus
{
    public function status(): Status;
}
