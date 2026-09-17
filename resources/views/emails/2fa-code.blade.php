<x:component::mail.shell
    title="Zwei-Faktor-Code"
    label="Sicherheit"
    preheader="Dein Anmelde-Code: gültig bis {{ $expiresLabel }} Uhr"
>
    <x:component::mail.heading
        title="Dein Zwei-Faktor-Code"
        :greeting="'Hallo '.$name.','"
    />

    <x:component::mail.sep />

    <x:component::mail.section>
        <p style="margin:0;font-size:15px;line-height:1.6;color:#0f172a;">
            verwende den folgenden Code, um deine Anmeldung abzuschließen.
        </p>
        <p style="margin:16px 0 0;font-size:15px;line-height:1.6;color:#475569;">
            Der Code ist aus Sicherheitsgründen nur begrenzt gültig.
        </p>
    </x:component::mail.section>

    <x:component::mail.sep />

    <x:component::mail.section padding="cta">
        <x:component::mail.code :code="$code" />
    </x:component::mail.section>

    <x:component::mail.section>
        <x:component::mail.panel title="Gültigkeit">
            Gültig bis: <strong style="color:#0f172a;">{{ $expiresLabel }}</strong> Uhr.
            Falls du diese Anmeldung nicht gestartet hast, kannst du diese E-Mail ignorieren.
        </x:component::mail.panel>
    </x:component::mail.section>
</x:component::mail.shell>
