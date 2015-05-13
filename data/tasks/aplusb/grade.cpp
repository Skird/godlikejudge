#include <cstdio>
#include <cstdlib>
#include <cassert>

#include <iostream>
#include <fstream>

using namespace std;

int getTestNumber(const string &prefix)
{
	system(("ls " + prefix + "/tests/??? | wc -l >testNumber").c_str());
	ifstream in("testNumber");
	assert(in.is_open());
	int n;
	in >> n;
	in.close();
	system("rm testNumber");
	return n;
}

const double maxscore = 100;

int main(int argc, char **argv)
{
	assert(argc >= 2);
	string prefix(argv[2]);
	int testN = getTestNumber(prefix), passed = 0;
	ofstream protocol(("./submissions/" + string(argv[1]) + ".result").c_str()), score(("./submissions/" + string(argv[1]) + ".score").c_str());
	protocol.precision(2);
	for (int i = 0; i < testN; i++)
	{
		string vd;
		int sc, tl, ml;
		cin >> vd >> sc >> tl >> ml;
		protocol << vd << " " << ((vd == "ok") ? (maxscore / testN) : 0) << " " << tl << " " << ml << endl;
		if (vd == "ok") passed++;
	}
	score << (int) ((double) maxscore * passed / testN + 0.5) << endl;
	score.close(), protocol.close();
	return 0;
}
