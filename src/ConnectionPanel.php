<?php declare(strict_types=1);

namespace OM\Doctrine\QueriesLogger;

use Nette\Database\Helpers;
use Tracy\Debugger;
use Tracy\Dumper;
use Tracy\IBarPanel;

class ConnectionPanel implements IBarPanel
{

    public float $totalTime = 0;
    public array $queries = [];

    public function startQuery(string $sql, ?array $params = null): void
    {
        Debugger::timer("doctrine");
        $this->queries[] = [$sql, $params, 0];
    }

    public function stopQuery(): void
    {
        $keys = array_keys($this->queries);
        $key = end($keys);
        $this->queries[$key][2] = Debugger::timer("doctrine");
        $this->totalTime += $this->queries[$key][2];
    }

    public function getTab(): string
    {
        return '<span title="Doctrine 3">'
            . '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAEYSURBVBgZBcHPio5hGAfg6/2+R980k6wmJgsJ5U/ZOAqbSc2GnXOwUg7BESgLUeIQ1GSjLFnMwsKGGg1qxJRmPM97/1zXFAAAAEADdlfZzr26miup2svnelq7d2aYgt3rebl585wN6+K3I1/9fJe7O/uIePP2SypJkiRJ0vMhr55FLCA3zgIAOK9uQ4MS361ZOSX+OrTvkgINSjS/HIvhjxNNFGgQsbSmabohKDNoUGLohsls6BaiQIMSs2FYmnXdUsygQYmumy3Nhi6igwalDEOJEjPKP7CA2aFNK8Bkyy3fdNCg7r9/fW3jgpVJbDmy5+PB2IYp4MXFelQ7izPrhkPHB+P5/PjhD5gCgCenx+VR/dODEwD+A3T7nqbxwf1HAAAAAElFTkSuQmCC" />'
            . count($this->queries) . ' queries'
            . ($this->totalTime ? ' / ' . sprintf('%0.1f', $this->totalTime * 1000) . 'ms' : '')
            . '</span>';
    }

    public function getPanel(): string
    {
        $s = '';
        foreach ($this->queries as $query) {
            $s .= $this->processQuery($query);
        }

        return empty($this->queries) ? '' :
            $this->renderStyles() .
            '<h1>Queries: ' . count($this->queries) . ($this->totalTime ? ', time: ' . sprintf('%0.3f', $this->totalTime * 1000) . ' ms' : '') . '</h1>
			<div class="nette-inner nette-Doctrine3Panel">
			<table>
			<tr><th>Time&nbsp;ms</th><th>SQL</th><th>Params</th></tr>' . $s . '
			</table>
			</div>';
    }

    protected function processQuery(array $query): string
    {
        $s = '';
        list($sql, $params, $time) = $query;

        $s .= '<tr><td>' . sprintf('%0.3f', $time * 1000);
        $s .= '</td><td class="nette-Doctrine3Panel-sql">' . Helpers::dumpSql($sql);
        $s .= '</td><td>' . Dumper::toHtml($params) . '</tr>';

        return $s;
    }

    protected function renderStyles(): string
    {
        return '
            <style> 
                #tracy-debug .nette-Doctrine3Panel { overflow:auto }
                #tracy-debug .nette-Doctrine3Panel table { margin: 8px 0; max-height: 150px } 
                #tracy-debug td.nette-Doctrine3Panel-sql { background: white !important }
            </style>
        ';
    }

}
