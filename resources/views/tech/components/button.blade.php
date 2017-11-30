<button class="list-group-item list-group-item-action" :class="{ active : '{{$title}}' == menuActive }" @click="menuActive = '{{ $title }}'">
    <h5 class="mb-1">{{ $title }}</h5>
    <p class="mb-1">{{ $description }}</p>
</button>
