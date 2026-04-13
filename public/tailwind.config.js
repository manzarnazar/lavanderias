tailwind.config = {
    theme: {
        extend: {
            fontFamily: {
                inter: ['Inter', 'sans-serif'],
                playfair: ['Playfair Display', 'sans-serif'],
            },
            maxWidth: {
                '2lg': '1140px', // custom max-width
            },
            colors: {
                'black': 'var(--color-black)',
                // mint palette
                'mint-50': 'var(--color-mint-50)',
                'mint-100': 'var(--color-mint-100)',
                'mint-200': 'var(--color-mint-200)',
                'mint-300': 'var(--color-mint-300)',
                'mint-400': 'var(--color-mint-400)',
                'mint-500': '#1A7FCF',
                'mint-600': '#006CBA',
                'mint-700': '#005AA0',
                'mint-800': 'var(--color-mint-800)',
                'mint-900': 'var(--color-mint-900)',

                // aqua palette
                'aqua-50': 'var(--color-aqua-50)',
                'aqua-100': 'var(--color-aqua-100)',
                'aqua-200': 'var(--color-aqua-200)',
                'aqua-300': 'var(--color-aqua-300)',
                'aqua-400': 'var(--color-aqua-400)',
                'aqua-500': 'var(--color-aqua-500)',
                'aqua-600': 'var(--color-aqua-600)',
                'aqua-700': 'var(--color-aqua-700)',
                'aqua-800': 'var(--color-aqua-800)',
                'aqua-900': 'var(--color-aqua-900)',

                // neutral palette
                'neutral-50': 'var(--color-neutral-50)',
                'neutral-100': 'var(--color-neutral-100)',
                'neutral-200': 'var(--color-neutral-200)',
                'neutral-300': 'var(--color-neutral-300)',
                'neutral-400': 'var(--color-neutral-400)',
                'neutral-500': 'var(--color-neutral-500)',
                'neutral-600': 'var(--color-neutral-600)',
                'neutral-700': 'var(--color-neutral-700)',
                'neutral-800': 'var(--color-neutral-800)',
                'neutral-900': 'var(--color-neutral-900)',

                // danger palette
                'danger-50': 'var(--color-danger-50)',
                'danger-100': 'var(--color-danger-100)',
                'danger-200': 'var(--color-danger-200)',
                'danger-300': 'var(--color-danger-300)',
                'danger-400': 'var(--color-danger-400)',
                'danger-500': 'var(--color-danger-500)',
                'danger-600': 'var(--color-danger-600)',
                'danger-700': 'var(--color-danger-700)',
                'danger-800': 'var(--color-danger-800)',
                'danger-900': 'var(--color-danger-900)',

                // warning palette
                'warning-50': 'var(--color-warning-50)',
                'warning-100': 'var(--color-warning-100)',
                'warning-200': 'var(--color-warning-200)',
                'warning-300': 'var(--color-warning-300)',
                'warning-400': 'var(--color-warning-400)',
                'warning-500': 'var(--color-warning-500)',
                'warning-600': 'var(--color-warning-600)',
                'warning-700': 'var(--color-warning-700)',
                'warning-800': 'var(--color-warning-800)',
                'warning-900': 'var(--color-warning-900)',
            },
            screens: {
                'xl-1': '1140px',   // iPhone SE, small Android
            },
        },
    },
}
