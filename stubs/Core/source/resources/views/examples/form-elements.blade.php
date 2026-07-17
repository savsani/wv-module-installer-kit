@extends('layouts.app')

@section('title', 'Form Elements')

@section('header')
    <x-ui.page-header title="Form Elements">
        Reference gallery for every <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">&lt;x-form.*&gt;</code> component.
        Border, background, shadow, and focus colors all come from the <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">form-input</code> /
        <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">form-accent</code> utilities in <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">resources/css/app.css</code> —
        change them there to restyle every field on the page at once.
    </x-ui.page-header>
@endsection

@section('content')
    <form class="space-y-6" onsubmit="return false">
        <x-ui.card-section title="Text, Password & Number Stepper Inputs">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-form.field label="Full name" badge="Text Input" for="name" hint="Enter your first and last name as it should appear on your profile.">
                    <x-form.input id="name" name="name" placeholder="Jane Cooper" />
                </x-form.field>

                <x-form.field label="Email" badge="Required" for="email" required hint="We'll use this to send you important account updates.">
                    <x-form.input id="email" type="email" name="email" placeholder="jane@example.com" />
                </x-form.field>

                <x-form.field label="Username" badge="Error State" for="username" error="This username is already taken.">
                    <x-form.input id="username" name="username" value="jane.cooper" error />
                </x-form.field>

                <x-form.field label="Website" badge="Success State" for="website" success="Looks good! This URL is valid.">
                    <x-form.input id="website" name="website" value="https://example.com" success />
                </x-form.field>

                <x-form.field label="Disabled" badge="Disabled State" for="disabled_field">
                    <x-form.input id="disabled_field" value="Can't touch this" disabled />
                </x-form.field>

                <x-form.field label="Read-only" badge="Read-only State" for="readonly_field" hint="This value is managed elsewhere and can't be edited here.">
                    <x-form.input id="readonly_field" value="Set elsewhere" readonly />
                </x-form.field>

                <x-form.field label="Password" badge="Password Input" for="password" hint="Must be at least 8 characters, including a number and a symbol.">
                    <x-form.password-input id="password" name="password" />
                </x-form.field>

                <x-form.field label="Current password" badge="Password — Error State" for="current_password" error="Incorrect password.">
                    <x-form.password-input id="current_password" name="current_password" error />
                </x-form.field>

                <x-form.field label="Quantity" badge="Number Stepper" for="quantity" hint="Use the − and + buttons or type a value directly. Range: 1–10.">
                    <x-form.number-stepper id="quantity" name="quantity" :value="2" :min="1" :max="10" />
                </x-form.field>

                <x-form.field label="Seats" badge="Number Stepper — Disabled" for="seats">
                    <x-form.number-stepper id="seats" name="seats" :value="5" :min="1" :max="10" disabled />
                </x-form.field>
            </div>
        </x-ui.card-section>

        <x-ui.card-section title="Textarea">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-form.field label="Bio" badge="Textarea" for="bio" hint="Write a few sentences about yourself. Visible on your public profile.">
                    <x-form.textarea id="bio" name="bio" placeholder="Tell us about yourself..." />
                </x-form.field>

                <x-form.field label="Bio" badge="Textarea — Error State" for="bio_error" error="Bio must be at least 20 characters.">
                    <x-form.textarea id="bio_error" name="bio_error" placeholder="Tell us about yourself..." error />
                </x-form.field>
            </div>
        </x-ui.card-section>

        <x-ui.card-section title="Rich Text Editor">
            <x-form.field label="Description" badge="Rich Text Editor" for="description">
                <x-form.rich-text-editor id="description" name="description" placeholder="Start writing a detailed description..." />
                <x-slot:hint>
                    Format text with the toolbar. Click
                    <span class="inline-flex items-center rounded bg-gray-100 px-1 py-0.5 font-mono text-xs text-gray-600 dark:bg-gray-800 dark:text-gray-400">&lt;&gt;</span>
                    to toggle HTML source view.
                </x-slot:hint>
            </x-form.field>
        </x-ui.card-section>

        @php
            $customHtmlSample = <<<'HTML'
            <style>
              .demo {
                color: red;
              }
            </style>
            <p>Hello world</p>
            <div class="demo">
              demo test
            </div>
            HTML;
        @endphp

        <x-ui.card-section title="Source Code Editor">
            <x-form.field label="Custom HTML" badge="Source Code Editor" for="custom_html" hint="Raw HTML/CSS/JS, kept exactly as typed — no WYSIWYG parsing. See the component's doc block for how to render this safely.">
                <x-form.source-code-editor id="custom_html" name="custom_html" :value="$customHtmlSample" />
            </x-form.field>
        </x-ui.card-section>

        <x-ui.card-section title="Checkbox, Toggle & Radio">
            <div class="grid gap-6 sm:grid-cols-3">
                <x-form.field label="Preferences" badge="Checkbox" hint="Choose which notifications and agreements apply to your account.">
                    <div class="space-y-3">
                        <x-form.checkbox name="terms" checked>Accept terms &amp; conditions</x-form.checkbox>
                        <x-form.checkbox name="newsletter">Subscribe to newsletter</x-form.checkbox>
                        <x-form.checkbox name="locked_checkbox" checked disabled>Locked setting</x-form.checkbox>
                    </div>
                </x-form.field>

                <x-form.field label="Notifications" badge="Toggle" hint="Choose which notifications you'd like to receive.">
                    <div class="space-y-3">
                        <x-form.toggle name="notifications" checked>Email notifications</x-form.toggle>
                        <x-form.toggle name="marketing">Marketing emails</x-form.toggle>
                        <x-form.toggle name="locked" disabled>Locked setting</x-form.toggle>
                    </div>
                </x-form.field>

                <x-form.field label="Plan" badge="Radio Group" hint="Choose the plan that best fits your needs.">
                    <x-form.radio-group name="plan" :options="['basic' => 'Basic', 'pro' => 'Pro', 'team' => 'Team']" selected="pro" />
                </x-form.field>

                <x-form.field label="Plan (locked)" badge="Radio Group — Disabled">
                    <x-form.radio-group name="plan_locked" :options="['basic' => 'Basic', 'pro' => 'Pro']" selected="basic" disabled />
                </x-form.field>
            </div>
        </x-ui.card-section>

        <x-ui.card-section title="Select">
            <div class="grid gap-5 sm:grid-cols-3">
                <x-form.field label="Native select" badge="Select" for="country" hint="Select the country you currently reside in.">
                    <x-form.select id="country" name="country" :options="['us' => 'United States', 'in' => 'India', 'gb' => 'United Kingdom']" placeholder="Select country" />
                </x-form.field>

                <x-form.field label="Country" badge="Select — Error State" for="country_error" error="Please select a country.">
                    <x-form.select id="country_error" name="country_error" :options="['us' => 'United States', 'in' => 'India', 'gb' => 'United Kingdom']" placeholder="Select country" error />
                </x-form.field>

                <x-form.field label="Multi-select" badge="Multi Select" for="skills" hint="Select all the skills that apply. You can choose multiple options.">
                    <x-form.multi-select id="skills" name="skills[]" :options="['php' => 'PHP', 'js' => 'JavaScript', 'laravel' => 'Laravel', 'vue' => 'Vue', 'alpine' => 'Alpine.js']" />
                </x-form.field>

                <x-form.field label="Skills (locked)" badge="Multi Select — Disabled">
                    <x-form.multi-select name="skills_locked[]" :options="['php' => 'PHP', 'js' => 'JavaScript']" disabled />
                </x-form.field>

                <x-form.field label="Search select" badge="Search Select" for="assignee" hint="Type to search and filter options.">
                    <x-form.search-select
                        id="assignee"
                        name="assignee"
                        placeholder="Assign to..."
                        :options="[
                            ['value' => 1, 'label' => 'Alex Morgan'],
                            ['value' => 2, 'label' => 'Priya Shah'],
                            ['value' => 3, 'label' => 'Sam Lee'],
                        ]"
                    />
                </x-form.field>

                <x-form.field label="Assignee (locked)" badge="Search Select — Disabled">
                    <x-form.search-select
                        name="assignee_locked"
                        placeholder="Assign to..."
                        :options="[['value' => 1, 'label' => 'Alex Morgan']]"
                        disabled
                    />
                </x-form.field>
            </div>
        </x-ui.card-section>

        <x-ui.card-section title="Date & Time">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-form.field label="Date" badge="Date Input" for="event_date" hint="Enter the date in DD/MM/YYYY format.">
                    <x-form.date-input id="event_date" name="event_date" />
                </x-form.field>

                <x-form.field label="Date" badge="Date Input — Error State" for="event_date_error" error="Please enter a valid date.">
                    <x-form.date-input id="event_date_error" name="event_date_error" error />
                </x-form.field>

                <x-form.field label="Time" badge="Time Input" for="event_time" hint="Choose your preferred time for the event.">
                    <x-form.time-input id="event_time" name="event_time" />
                </x-form.field>

                <x-form.field label="Time" badge="Time Input — Error State" for="event_time_error" error="Please choose a time.">
                    <x-form.time-input id="event_time_error" name="event_time_error" error />
                </x-form.field>

                <x-form.field label="Date" badge="Date Picker" for="event_date_picker" hint="Calendar-style picker, click to choose a date.">
                    <x-form.date-picker id="event_date_picker" name="event_date_picker" />
                </x-form.field>

                <x-form.field label="Date" badge="Date Picker — Error State" for="event_date_picker_error" error="Please choose a date.">
                    <x-form.date-picker id="event_date_picker_error" name="event_date_picker_error" error />
                </x-form.field>

                <x-form.field label="Booking Date" badge="Date Picker — Restricted" for="booking_date" hint="Past dates and weekends are disabled.">
                    <x-form.date-picker
                        id="booking_date"
                        name="booking_date"
                        format="MMM DD, YYYY"
                        :disable-past="true"
                        :disabled-days-of-week="[0, 6]"
                    />
                </x-form.field>

                <x-form.field label="Date (locked)" badge="Date Picker — Disabled" for="event_date_picker_locked">
                    <x-form.date-picker id="event_date_picker_locked" name="event_date_picker_locked" value="2026-07-13" disabled />
                </x-form.field>
            </div>
        </x-ui.card-section>

        <x-ui.card-section title="Range & Slider">
            <div class="grid gap-6 sm:grid-cols-2">
                <x-form.field label="Opacity (0–1)" badge="Range Input" for="opacity" hint="Opacity value (0 to 1).">
                    <x-form.range-input id="opacity" name="opacity" :value="0.75" :min="0" :max="1" :step="0.01" :decimals="2" />
                </x-form.field>

                <x-form.field label="Opacity (locked)" badge="Range Input — Disabled">
                    <x-form.range-input name="opacity_locked" :value="0.5" :min="0" :max="1" :step="0.01" :decimals="2" disabled />
                </x-form.field>

                <x-form.field label="Budget" badge="Range Slider" for="budget" hint="Drag the slider to set your monthly budget.">
                    <x-form.range-slider id="budget" name="budget" :value="450" :min="0" :max="1000" prefix="$" />
                </x-form.field>

                <x-form.field label="Budget (locked)" badge="Range Slider — Disabled">
                    <x-form.range-slider name="budget_locked" :value="300" :min="0" :max="1000" prefix="$" disabled />
                </x-form.field>
            </div>
        </x-ui.card-section>

        <x-ui.card-section title="File Upload">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-form.field label="Attachment" badge="File Input" hint="PDF or Word, up to 5MB.">
                    <x-form.file-input name="attachment" extensions="pdf,doc,docx" :max-size="5" />
                </x-form.field>

                <x-form.field label="Disabled" badge="Disabled State">
                    <x-form.file-input name="attachment_locked" disabled />
                </x-form.field>
            </div>
        </x-ui.card-section>

        <x-ui.card-section title="Color">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-form.field label="Brand color" badge="Color Picker (Hex)" for="brand_color" hint="Pick a color or enter a hex value directly.">
                    <x-form.color-picker id="brand_color" name="brand_color" />
                </x-form.field>

                <x-form.field label="Overlay color (RGBA)" badge="Color Picker (RGBA)" for="overlay_color" hint="Adjust the color and opacity for overlays.">
                    <x-form.color-picker id="overlay_color" name="overlay_color" type="rgba" />
                </x-form.field>
            </div>
        </x-ui.card-section>

        <x-ui.card-section title="Cover Image">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-form.field label="Cover image" badge="Crop — 16:9" for="cover_image" hint="Select an image, then drag/resize the selection to crop it to a 16:9 banner.">
                    <x-form.cover-image id="cover_image" name="cover_image" :aspect-ratio="16 / 9" :crop-width="1280" :crop-height="720" :max-size="5" />
                </x-form.field>

                <x-form.field label="Profile banner" badge="Crop — Square, fixed output" for="square_image" hint="Locked to a 1:1 selection, exported at exactly 400×400px.">
                    <x-form.cover-image id="square_image" name="square_image" :aspect-ratio="1" :crop-width="400" :crop-height="400" />
                </x-form.field>

                <x-form.field label="Freeform crop" badge="Crop — No aspect ratio" for="freeform_image" hint="No aspect ratio is enforced — resize the selection to any shape.">
                    <x-form.cover-image id="freeform_image" name="freeform_image" :aspect-ratio="null" />
                </x-form.field>

                <x-form.field label="Logo upload" badge="Crop Disabled" for="no_crop_image" hint="Cropping turned off — uploads the original file as-is.">
                    <x-form.cover-image id="no_crop_image" name="no_crop_image" :crop-enabled="false" />
                </x-form.field>
            </div>
        </x-ui.card-section>
    </form>
@endsection
