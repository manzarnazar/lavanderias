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
                'mint-50': '#eff6ff',
                'mint-100': '#dbeafe',
                'mint-200': '#bfdbfe',
                'mint-300': '#93c5fd',
                'mint-400': '#60a5fa',
                'mint-500': '#1A7FCF',
                'mint-600': '#006CBA',
                'mint-700': '#005AA0',
                'mint-800': '#1e40af',
                'mint-900': '#1e3a8a',

                // aqua palette
                'aqua-50': '#f0f9ff',
                'aqua-100': '#e0f2fe',
                'aqua-200': '#bae6fd',
                'aqua-300': '#7dd3fc',
                'aqua-400': '#38bdf8',
                'aqua-500': '#0ea5e9',
                'aqua-600': '#0284c7',
                'aqua-700': '#0369a1',
                'aqua-800': '#075985',
                'aqua-900': '#0c4a6e',

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
