<div class="flex min-h-screen">
    <!-- Form Kiri -->
    <div class="flex-1 flex justify-center items-center">
        <div class="w-80 max-w-80 space-y-6">
            <div class="flex justify-center opacity-50">
                <a href="/" class="group flex items-center gap-3">
                    <flux:icon.building-storefront class="h-6 w-6 text-zinc-800 dark:text-white" />
                    <span class="text-xl font-semibold text-zinc-800 dark:text-white">Sign Up</span>
                </a>
            </div>

            <flux:heading class="text-center" size="xl">Create an account</flux:heading>

            <form wire:submit="register" class="flex flex-col gap-6">
                <!-- Name -->
                <flux:input wire:model="name" label="Full Name" type="text" placeholder="John Doe" />

                <!-- Email -->
                <flux:input wire:model="email" label="Email Address" type="email" placeholder="email@example.com" />

                <!-- Password -->
                <flux:input wire:model="password" label="Password" type="password"
                    placeholder="At least 8 characters" />

                <!-- Confirm Password -->
                <flux:input wire:model="password_confirmation" label="Confirm Password" type="password"
                    placeholder="Repeat your password" />

                <!-- Submit Button -->
                <flux:button type="submit" variant="primary" class="w-full">Sign Up</flux:button>
            </form>

            <flux:subheading class="text-center">
                Already have an account? <flux:link href="{{ route('login') }}" wire:navigate>Log in here</flux:link>
            </flux:subheading>
        </div>
    </div>

    <!-- Sisi Kanan (Gambar/Aura) -->
    <div class="flex-1 p-4 max-lg:hidden">
        <div class="text-white relative rounded-lg h-full w-full bg-zinc-900 flex flex-col items-start justify-end p-16"
            style="background-image: url('/img/demo/auth_aurora_2x.png'); background-size: cover">
        </div>
    </div>
</div>
