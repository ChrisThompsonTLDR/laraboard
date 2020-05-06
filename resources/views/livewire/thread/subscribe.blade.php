<div class="d-inline">
    @auth
    <button wire:click="subscribe" wire:dirty.class="subscribed" class="btn btn-danger btn-sm @if ($subscribed){{ 'd-none' }}@endif"><i class="fas fa-bell"></i><span> Subscribe</span></button>

    <button wire:click="unsubscribe" wire:dirty.class="unsubscribe" class="btn btn-danger btn-sm @if (!$subscribed){{ 'd-none' }}@endif"><i class="fas fa-bell-slash"></i><span> Unsubscribe</span></button>
    @endauth
</div>
