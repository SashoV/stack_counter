<script setup>
import { Head, Link } from '@inertiajs/vue3';
import StackCounterForm from '@/Components/StackCounterForm.vue'
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>

    <Head title="Welcome" />
    <div class="flex min-h-screen flex-col bg-gray-100 text-black/50 dark:bg-black dark:text-white/50">
        <!-- Header -->
        <header class="w-full bg-white">
            <div class="mx-auto w-full max-w-7xl px-6 py-10 grid grid-cols-2 items-center gap-2 lg:grid-cols-3">
                <div class="flex lg:col-start-2 lg:justify-center">
                    <!-- Logo area (optional) -->
                </div>
                <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard.calculations')"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                    Dashboard
                    </Link>

                    <template v-else>
                        <Link :href="route('login')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                        Log in
                        </Link>

                        <Link v-if="canRegister" :href="route('register')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                        Register
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow">
            <div class="mx-auto w-full max-w-7xl px-6 mt-6">
                <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                    <div>
                        <h1 class="text-xl mb-4">Books</h1>
                        <p>
                            Many books have arrived in the library of the Aglargond School of Magic, and they need to be
                            arranged on the
                            shelves. On the library floor, tiled with equal square tiles, librarians marked a
                            square‐shaped area
                            (length of the side of this square is N
                            square tiles) for temporary storage of the books. Books were either stacked on top of the
                            other
                            books or on the empty tiles in the marked area in such fashion that a stack of books is
                            formed on
                            some tiles.</p>
                        <p>The youngest student got an assignment to enter information about every book into the
                            catalogue and
                            arrange the books to their
                            shelves. After hearing this news, he just stood beside the books and sighed burdened by the
                            amount
                            of work he had to do.
                            Going alongside the edges of the marked area he looks in directions parallel to sides of the
                            marked area and counts stacks of books visible. A stack is visible if there is no higher
                            stack
                            or stack of equal height between it and the student.
                        </p>
                    </div>
                    <div>
                        <img src="/images/books.jpg" alt="Books" />
                    </div>
                </div>
                <p class="mt-4">This is a software solution that counts the number of stacks visible to the young
                    magician while walking beside the books.</p>
                <p v-if="!$page.props.auth.user">Please
                    <Link :href="route('register')"
                        class="rounded-md py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                    register
                    </Link>
                    if you want to try it out! If you already have an account, please
                    <Link :href="route('login')"
                        class="rounded-md py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                    log in!
                    </Link>
                </p>
            </div>
            <StackCounterForm v-if="$page.props.auth.user" />
        </main>

        <!-- Footer -->
        <footer class="py-8 text-center text-sm">
            {{ appName }}
        </footer>
    </div>
</template>
