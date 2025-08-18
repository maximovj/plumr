<div>
    @if(session('app-error'))
        <div
            x-data="{ show: true, timer: null }"
            x-init="timer = setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition
            class="bg-red-500 text-white p-4 mb-4 flex justify-between items-center"
        >
            <span>{{ session('app-error') }}</span>
            <button
                @click="show = false; clearTimeout(timer)"
                class="ml-4 font-bold text-white hover:text-gray-200"
            >
                &times;
            </button>
        </div>
    @endif
</div>
