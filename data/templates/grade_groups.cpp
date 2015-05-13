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
const int groupn = 10, maxtests = 1000;

int getGroupId(int test)
{
  return max(0, test - 2);
}

int getGroupSz(int id)
{
  return id ? 1 : 3;
}

double getGroupCost(int id)
{
  return id ? 100.0 / 9.0 : 0.0;
}

string verdict[maxtests];
double sc[maxtests], tl[maxtests];
int ml[maxtests];
int passgroup[groupn];

int main(int argc, char **argv)
{
   assert(argc >= 2);
   string prefix(argv[2]);
   int testN = getTestNumber(prefix), passed = 0;
   ofstream protocol(("./submissions/" + string(argv[1]) + ".result").c_str()), score(("./submissions/" + string(argv[1]) + ".score").c_str());
   
   for (int i = 0; i < groupn; i++) passgroup[i] = 1;
   for (int i = 0; i < testN; i++)
   {
      cin >> verdict[i] >> sc[i] >> tl[i] >> ml[i];
      if (verdict[i] != "ok") passgroup[getGroupId(i)] = 0;
   }
   protocol.precision(2);
        protocol << fixed;
   double total = 0;
   for (int i = 0; i < testN; i++)
   {
      int id = getGroupId(i);
      sc[i] = (getGroupCost(id) / getGroupSz(id)) * passgroup[id];
      protocol << verdict[i] << " " << sc[i] << " " << tl[i] << " " << ml[i] << endl;
      total += sc[i];
   }
   score << (int) (total + 0.5) << endl;
   score.close(), protocol.close();
   return 0;
}
