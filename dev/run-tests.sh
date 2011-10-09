DIR="$( cd -P "$( dirname "$0" )" && pwd )"
phpunit --coverage-html $DIR/results/coverage --testdox-html $DIR/results/testdox/index.html --colors $DIR/tests 
phpmd $DIR/../lib/ html codesize,unusedcode,naming,design --reportfile $DIR/results/mess/index.html
