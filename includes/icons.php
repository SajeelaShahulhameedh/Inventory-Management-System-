<?php
/**
 * ICON HELPER
 * Renders small inline SVG line-icons (stroke = currentColor, so each icon
 * automatically inherits the surrounding text/button color).
 *
 * Usage: echo icon('box', 20);
 *        echo icon('box', 20, 'my-css-class');
 */

if (!function_exists('icon')) {
    function icon(string $name, int $size = 18, string $class = ''): string
    {
        $attrs = 'width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" '
            . 'fill="none" stroke="currentColor" stroke-width="2" '
            . 'stroke-linecap="round" stroke-linejoin="round" '
            . 'class="icon icon-' . htmlspecialchars($name) . ($class ? ' ' . htmlspecialchars($class) : '') . '" '
            . 'aria-hidden="true" focusable="false"';

        $paths = [
            'home'          => '<path d="M3 11.5 12 4l9 7.5"/><path d="M5 10v9a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1v-9"/>',
            'box'           => '<path d="M21 8 12 3 3 8v8l9 5 9-5V8Z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/>',
            'layers'        => '<path d="M12 3 3 8l9 5 9-5-9-5Z"/><path d="M3 13l9 5 9-5"/><path d="M3 18l9 5 9-5"/>',
            'repeat'        => '<path d="M17 2 21 6l-4 4"/><path d="M3 12v-2a4 4 0 0 1 4-4h14"/><path d="M7 22 3 18l4-4"/><path d="M21 12v2a4 4 0 0 1-4 4H3"/>',
            'alert-triangle'=> '<path d="M10.3 3.6 1.8 18a1 1 0 0 0 .9 1.5h18.6a1 1 0 0 0 .9-1.5L13.7 3.6a1 1 0 0 0-1.7 0Z"/><path d="M12 9v4"/><path d="M12 17h.01"/>',
            'building'      => '<rect x="4" y="3" width="16" height="18" rx="1"/><path d="M9 21v-4h6v4"/><path d="M8 7h1"/><path d="M15 7h1"/><path d="M8 11h1"/><path d="M15 11h1"/><path d="M8 15h1"/><path d="M15 15h1"/>',
            'bar-chart'     => '<path d="M3 21h18"/><rect x="6" y="11" width="3" height="7"/><rect x="13" y="7" width="3" height="11"/><path d="M19 4h-3v14h3"/>',
            'grid'          => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
            'plus'          => '<path d="M12 5v14"/><path d="M5 12h14"/>',
            'search'        => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
            'tag'           => '<path d="M12.6 2H4a2 2 0 0 0-2 2v8.6a2 2 0 0 0 .59 1.41l9 9a2 2 0 0 0 2.82 0l7.6-7.6a2 2 0 0 0 0-2.82l-9-9A2 2 0 0 0 12.6 2Z"/><circle cx="7.5" cy="7.5" r="1.5"/>',
            'dollar'        => '<path d="M12 1v22"/><path d="M17 5.5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
            'arrow-left'    => '<path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>',
            'arrow-right'   => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
            'chevron-right' => '<path d="m9 18 6-6-6-6"/>',
            'printer'       => '<path d="M6 9V3h12v6"/><path d="M6 18H4a1 1 0 0 1-1-1v-5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5a1 1 0 0 1-1 1h-2"/><rect x="6" y="14" width="12" height="7"/>',
            'save'          => '<path d="M17 21v-8H7v8"/><path d="M7 3v5h9"/><path d="M5 3h11l4 4v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z"/>',
            'check-circle'  => '<circle cx="12" cy="12" r="9"/><path d="m8.5 12.3 2.4 2.4 4.6-5.2"/>',
            'alert-circle'  => '<circle cx="12" cy="12" r="9"/><path d="M12 8v5"/><path d="M12 16h.01"/>',
            'clock'         => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
            'code'          => '<path d="m9 8-4 4 4 4"/><path d="m15 8 4 4-4 4"/>',
            'info'          => '<circle cx="12" cy="12" r="9"/><path d="M12 16v-5"/><path d="M12 8h.01"/>',
            'arrow-down-circle' => '<circle cx="12" cy="12" r="9"/><path d="M12 8v7"/><path d="m8.5 12.5 3.5 3 3.5-3"/>',
            'arrow-up-circle'   => '<circle cx="12" cy="12" r="9"/><path d="M12 16V9"/><path d="m8.5 12.5 3.5-3 3.5 3"/>',
            'sliders'       => '<path d="M4 21v-7"/><path d="M4 10V3"/><path d="M12 21v-9"/><path d="M12 8V3"/><path d="M20 21v-5"/><path d="M20 12V3"/><path d="M2 14h4"/><path d="M10 8h4"/><path d="M18 12h4"/>',
            'calendar'      => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>',
            'users'         => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
            'hash'          => '<path d="M4 9h16"/><path d="M4 15h16"/><path d="m10 3-2 18"/><path d="m16 3-2 18"/>',
            'clipboard'     => '<rect x="6" y="4" width="12" height="17" rx="1.5"/><path d="M9 4V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1"/><path d="M9 10h6"/><path d="M9 14h6"/><path d="M9 18h3"/>',
        ];

        $body = $paths[$name] ?? $paths['info'];
        return '<svg ' . $attrs . '>' . $body . '</svg>';
    }
}
