<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Twig_Tests_Profiler_Dumper_BlackfireTest extends Twig_Tests_Profiler_Dumper_AbstractTest
{
    public function testDump()
    {
        $dumper = new Twig_Profiler_Dumper_Blackfire();

        $this->assertStringMatchesFormat(<<<EOF
file-format: BlackfireProbe
cost-dimensions: wt mu pmu
request-start: %d.%d

main()//1 %d %d %d
main()==>materialize_test.twig//1 %d %d %d
materialize_test.twig==>embedded.twig::block(body)//1 %d %d 0
materialize_test.twig==>embedded.twig//2 %d %d %d
embedded.twig==>included.twig//2 %d %d %d
materialize_test.twig==>materialize_test.twig::macro(foo)//1 %d %d %d
EOF
        , $dumper->dump($this->getProfile()));
    }
}
