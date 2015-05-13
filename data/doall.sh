function compile()
{
   g++ $1.cpp -o $1 -static -fno-optimize-sibling-calls -fno-strict-aliasing -lm -s -x c++ -O2
}

function fail()
{
   echo $1
   exit 111
}

compile runner
compile tester
compile judge
compile testing
