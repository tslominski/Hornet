DIR="$( cd -P "$( dirname "$0" )" && pwd )"
phpunit --coverage-html $DIR/results/coverage --colors $DIR/tests/Hornet/Data/Entities/URI.php 
