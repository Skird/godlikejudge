#include <ctime>
#include <cstdio>
#include <cstdlib>
#include <cassert>

#include <algorithm>
#include <iostream>
#include <fstream>

using namespace std;

const int _fail = 1;
const int _fail_to_run = 2;
const int _fail_to_check = 3;

const string vname[] = {"ok", "tl", "re", "wa", "ml", "pc"};
const int _ok = 0, _tl = 1, _re = 2, _wa = 3, _ml = 4, _pc = 5;

double invTime;
int invMem;

string toString(int x, int k)
{
    string ans;
    for (int i = 0; i < k; i++, x /= 10) 
   ans += (char) (x % 10 + '0');
    reverse(ans.begin(), ans.end());
    return ans;
}

inline void fetchTimeAndMem()
{
   ifstream in("./sandbox/resourceConsumption");
    if (!in.is_open())
    {
      cerr << "Unable to determine used time and memory" << endl;
      exit(_fail);
    }
    in >> invTime >> invMem;
    in.close();
}

inline void printTimeAndMem()
{
    cout << " " << (int) (invTime * 1000) << " " << (int) invMem << endl;
}

inline int getInvocationResult()
{
    ifstream in("./sandbox/invocationResult");
    if (!in.is_open())
    {
      cerr << "Unable to fetch invocation result" << endl;
      exit(_fail_to_run);
    }
    int res;
    in >> res;
    in.close();
    return res;
}

int getTestNumber(const string &prefix)
{
    system(("ls " + prefix + "/tests/??? | wc -l >./sandbox/testNumber").c_str());
    ifstream in("./sandbox/testNumber");
    if (!in.is_open())
    {
      cerr << "Unable to determine number of tests in problem" << endl;
      exit(_fail);
    }
    int tests;
    in >> tests;
    in.close();
    system("rm ./sandbox/testNumber");
    return tests;
}

inline int getCheckerExitCode()
{
    ifstream in("./sandbox/checkerExitCode");
    if (!in.is_open())
    {
      cout << "Unable to fetch checker exit code" << endl;
      exit(_fail);
    }
    int code;
    in >> code;
    in.close();
    return code;
}

int getTimeLimit(const string &problemPath)
{
    ifstream st((problemPath + "/timelimit").c_str());
    if (!st.is_open())
    {
      cerr << "Cannot fetch timelimit for problem" << endl;
      exit(_fail);
    }
    int timelimit;
    st >> timelimit;
    st.close();
    return timelimit;
}

int getMemoryLimit(const string &problemPath)
{
    ifstream st((problemPath + "/memorylimit").c_str());
    if (!st.is_open())
    {
      cerr << "Cannot fetch memory limit for problem" << endl;
      exit(_fail);
    }
    int memlimit;
    st >> memlimit;
    st.close();
    return memlimit;
}

double getPartialScore()
{
   ifstream st("./sandbox/checkerOutput");
    if (!st.is_open())
    {
      cerr << "Cannot fetch partail score from checker output" << endl;
      exit(_fail);
    }
    double score;
    st >> score;
    st.close();
    return score;
}

int main(int argc, char **argv)
{
    string prefix(argv[1]);
    cerr << "Prefix = " << prefix << endl;
    int testNumber = getTestNumber(prefix);
    cerr << "Test count = " << testNumber << endl;
    assert(testNumber <= 999);
    cout.precision(2);
    cout << fixed;
    for (int i = 1; i <= testNumber; i++)
    {
      cerr << "Test #" << i << endl;
      string testNum = toString(i, 3);
      if (system(("cp -f " + prefix + "/tests/" + testNum + " ./sandbox/test.in").c_str()))
      {
         cerr << "IO ERROR: unable to copy test" << endl;
         exit(_fail);
      }
      if (system(("cp -f " + prefix + "/tests/" + testNum + ".ans ./sandbox/test.ans").c_str()))
      {
         cerr << "IO ERROR: unable to copy test" << endl;
         exit(_fail);
      }
      cerr << "Input copied" << endl;
      if (system(("./runner " + prefix + " >./sandbox/invocationResult").c_str())) exit(_fail_to_run);
      cerr << "Runned" << endl;
      fetchTimeAndMem();
      int verdict = getInvocationResult();
      if (verdict != _ok) cout << vname[verdict] << " 0";
      else if (invMem > getMemoryLimit(prefix)) cout << vname[_ml] << " 0";
      else
      {
         system((prefix + "/checker >./sandbox/checkerOutput 2>./sandbox/checkerOutput ./sandbox/test.in ./sandbox/test.out ./sandbox/test.ans; echo $? >./sandbox/checkerExitCode").c_str());
         int code = getCheckerExitCode();
         if (code == 3) cout << vname[_pc] << " " << getPartialScore();
         else if (code == 1 || code == 2) cout << vname[_wa] << " 0";
         else if (code == 0) cout << vname[_ok] << " 1";
         else exit(_fail_to_check);
      }
      cerr << "Checked" << endl;
      if (verdict != _tl) printTimeAndMem();
      else cout << " " << getTimeLimit(prefix) << " 0" << endl;
      //system("rm -f ./sandbox/test.in ./sandbox/test.out ./sandbox/test.ans ./sandbox/invocationResult ./sandbox/resourceConsumption");
      cerr << "Finished" << endl;
    }
    return 0;
}
