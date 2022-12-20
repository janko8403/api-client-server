<?php

namespace Hr\Service;

use Laminas\Json\Json;

class CronService
{
    /**
     * Fetches cron details.
     *
     * @return string
     */
    public function fetch(): string
    {
        $cron = shell_exec('crontab -l');
//        $cron = file_get_contents('crontab');
        $parts = preg_split('/(###? .*)/', $cron, -1, PREG_SPLIT_DELIM_CAPTURE);
        unset($parts[0]);

        $html = [];
        foreach ($parts as $part) {
            $html[] = $this->format($part);
        }

        return implode('', $html);
    }

    public function normalizeIID(string $command): string
    {
        $iid = getenv('IID');
        $dir = $iid;
        switch ($iid) {
            case '99':
                $dir = 'tikrow-demo';
                break;
            case '100':
                $dir = 'tikrow-dev';
                break;
        }


        return preg_replace(
            ['/slab3prod\/www\/(.+)\/public/', '/ (\d+) /'],
            ["slab3prod/www/$dir/public", " $iid "],
            $command
        );
    }

    /**
     * Formats cron part.
     *
     * @param string $part
     * @return string
     */
    private function format(string $part): string
    {
        if (preg_match('/### (.*)/', $part, $matches)) {
            return "<h3>$matches[1]</h3>";
        } elseif (preg_match('/## (.*)/', $part, $matches)) {
            return "<h4>$matches[1]</h4>";
        } else {
            return $this->formatTable(trim($part));
        }
    }

    /**
     * Formats cron details as table.
     *
     * @param string $cron
     * @return string
     */
    private function formatTable(string $cron): string
    {
        if (empty($cron)) return '';

        $html = <<<HTML
<table class="table">
<thead>
    <tr>
        <th>Minuty</th>
        <th>Godziny</th>
        <th>Dzień miesiąca</th>
        <th>Miesiąc</th>
        <th>Dzień tygodnia</th>
        <th>Polecenie</th>
        <th></th>
    </tr>
</thead>
<tbody>{BODY}</tbody>
</table>
HTML;
        $parts = explode("\n", $cron);

        $innerHtml = '';
        foreach ($parts as $part) {
            if (substr($part, 0, 1) == '#') continue;

            preg_match('/(.+) (.+) (.+) (.+) (.+) \((.+)\)/', $part, $matches);
            $command = preg_replace(
                [
                    '/(index.php) (.+) /U',
                    '/(consumer -c) (.+) /U',
                ], '$1 <strong>$2</strong> ',
                $matches[6]
            );
            $encoded = Json::encode(['command' => $matches[6]]);

            $innerHtml .= <<<HTML
<tr>
    <td>$matches[1]</td>
    <td>$matches[2]</td>
    <td>$matches[3]</td>
    <td>$matches[4]</td>
    <td>$matches[5]</td>
    <td>$command</td>
    <td><button class="btn btn-sl btn-xs btn-execute" data-payload='$encoded'>Wykonaj</button> </td>
</tr>
HTML;
        }

        return str_replace('{BODY}', $innerHtml, $html);
    }
}