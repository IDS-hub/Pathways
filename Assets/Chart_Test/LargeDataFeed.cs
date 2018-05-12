using UnityEngine;
using ChartAndGraph;
using System.Collections.Generic;
using System.Collections;
using System;
using System.Linq;
using UnityEngine.SceneManagement;

public class LargeDataFeed : WindowController, IComparer<DoubleVector2>, IComparer<StatPoint> {

	public static Action<bool> OnLandScape;

	List<DoubleVector2> mData = new List<DoubleVector2>();
	List<StatPoint> mStat = new List<StatPoint>();

	double pageSize = double.MaxValue;
	double currentPagePosition = 0.0;
	[SerializeField] GraphChartBase graph;

	public override void Start() {
		base.Start();

		///Screen.orientation = ScreenOrientation.Landscape;
		if (OnLandScape != null)
			OnLandScape(true);

		Loader.Instane.ShowLoading();
		// getting the statistic graph data
		apiManager.GetUserStatistics((jsonData, success) => {
			Loader.Instane.RemoveLoading();
			if (success) {
				MiniJsonArray sesionArray = jsonData.GetJsonArray("res");
				//if (sesionArray != null && sesionArray.Count > 0) {

				for (int i = 0; i < sesionArray.Count; i++) {
					int painLevel = sesionArray.Get(i).GetField("painLevel", 0) / sesionArray.Get(i).GetField("hitCount", 1);
					double time = ChartDateUtility.DateToValue(Convert.ToDateTime(sesionArray.Get(i).GetField("sessionDate", "")));
					mStat.Add(new StatPoint(time, painLevel));

					/*//time = ChartDateUtility.DateToValue(Convert.ToDateTime("2018-03-22"));
					mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-03-22")), 8));
					mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-03-29")), 7));
					mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-04-10")), 8));
					mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-04-15")), 6));
					mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-04-23")), 5));
					mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-05-01")), 4));
					mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-05-4")), 5));
					mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-05-13")), 3));
					mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-05-17")), 2));
					mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-05-25")), 1));*/
				}

				mStat = mStat.OrderBy(o => o.x).ToList();

				ShowGraph();

				/*}
				else{
					Popup.Instance.ShowPopup("Attention", "No data to show", () => {
						OnClickCross();
					});
				}*/
			} else {
				// user does not authenticated, logout
				Popup.Instance.ShowPopup(Constants.INVALID_SESSION_TITLE, Constants.INVALID_SESSION_DESC, () => {
					OnClickCross();
				});
			}
		});
	}

	public void OnClickCross() {
		//SceneManager.LoadScene(0);
		Destroy(gameObject);
	}

	public void OnDisable() {
		if (OnLandScape != null)
			OnLandScape(false);
	}

	public void OnDestroy() {
		if (OnLandScape != null)
			OnLandScape(false);
	}

	void ShowGraph() {
		//graph = GetComponent<GraphChartBase>();

		/*mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-03-21")), 3));
		mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-03-22")), 6));
		mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-03-24")), 2));
		mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-03-28")), 1));
		mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-03-29")), 4));
		mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-04-2")), 7));
		mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-04-10")), 4));
		mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-04-15")), 1));
		mStat.Add(new StatPoint(ChartDateUtility.DateToValue(Convert.ToDateTime("2018-04-17")), 9));*/

		graph.DataSource.AutomaticHorizontalView = false;
		graph.DataSource.HorizontalViewOrigin = mStat[0].x;
		graph.DataSource.HorizontalViewSize = ChartDateUtility.TimeSpanToValue(TimeSpan.FromDays(8));


		double currentPageIndex = mStat[mStat.Count - 1].x - ChartDateUtility.TimeSpanToValue(TimeSpan.FromDays(7));

		int StartIndex = InitFindClosestIndex(currentPageIndex);
		
		Debug.Log("startIndex " + StartIndex);

		for (int i = 0; i < StartIndex && i < mStat.Count; i++) {
			mData.Add(new DoubleVector2(mStat[i].x, mStat[i].y));
		}

		LoadPage(currentPageIndex);

		Debug.Log("mData " + mData.Count);

		StartCoroutine("Animate", StartIndex);
	}

	IEnumerator Animate(int startIndex) {
		for (int i = startIndex; i < mStat.Count; i++) {
			AppendPointRealtime(mStat[i].x, mStat[i].y, .5f);
			yield return new WaitForSeconds(.5f);
		}
	}

	public void AppendPointRealtime(double x, double y, double slideTime = 0f) {
		if (graph == null)
			return;
		bool show = false;
		if (mData.Count == 0)
			show = true;
		else {
			double viewX = mData[mData.Count - 1].x;
			double pageStartThreshold = currentPagePosition - pageSize;
			double pageEndThreshold = currentPagePosition + pageSize - graph.DataSource.HorizontalViewSize;
			if (viewX >= pageStartThreshold && viewX <= pageEndThreshold)
				show = true;
		}
		mData.Add(new DoubleVector2(x, y));
		if (show)
			graph.DataSource.AddPointToCategoryRealtime("painpoint", x, y, slideTime);
	}

	int FindClosestIndex(double position) { // if you want to know what is index is currently displayed . use binary search to find it
		//NOTE :: this method assumes your data is sorted !!! 
		int res = mData.BinarySearch(new DoubleVector2(position, 0.0), this);
		if (res >= 0)
			return res;
		return ~res;
	}

	int InitFindClosestIndex(double position) { // if you want to know what is index is currently displayed . use binary search to find it
		//NOTE :: this method assumes your data is sorted !!! 
		int res = mStat.BinarySearch(new StatPoint(position, 0.0), this);
		if (res >= 0)
			return res;
		return ~res;
	}

	void findPointsForPage(double position, out int start, out int end) { // given a page position , find the right most and left most indices in the data for that page. 
		int index = FindClosestIndex(position);
		int i = index;
		double endPosition = position + pageSize;
		double startPosition = position - pageSize;

		//starting from the current index , we find the page boundries
		for (start = index; start > 0; start--) {
			if (mData[i].x < startPosition) // take the first point that is out of the page. so the graph doesn't break at the edge
                break;
		}
		for (end = index; end < mData.Count; end++) {
			if (mData[i].x > endPosition) // take the first point that is out of the page
                break;
		}
	}

	private void Update() {
		if (graph != null) {
			//check the scrolling position of the graph. if we are past the view size , load a new page
			double pageStartThreshold = currentPagePosition - pageSize;
			double pageEndThreshold = currentPagePosition + pageSize - graph.DataSource.HorizontalViewSize;
			if (graph.HorizontalScrolling < pageStartThreshold || graph.HorizontalScrolling > pageEndThreshold) {
				LoadPage(graph.HorizontalScrolling);
			}
		}
	}

	void LoadPage(double pagePosition) {

		if (graph != null) {

			//           Debug.Log("Loading page :" + pagePosition);
			graph.DataSource.StartBatch(); // call start batch 
			graph.DataSource.HorizontalViewOrigin = 0;
			int start, end;
			findPointsForPage(pagePosition, out start, out end); // get the page edges
			graph.DataSource.ClearCategory("painpoint"); // clear the cateogry
			for (int i = start; i < end; i++) // load the data
				graph.DataSource.AddPointToCategory("painpoint", mData[i].x, mData[i].y);
			graph.DataSource.EndBatch();
			graph.HorizontalScrolling = pagePosition;
		}
		currentPagePosition = pagePosition;
	}

	public int Compare(DoubleVector2 x, DoubleVector2 y) {
		if (x.x < y.x)
			return -1;
		if (x.x < y.x)
			return 1;
		return 0;
	}

	public int Compare(StatPoint x, StatPoint y) {
		if (x.x < y.x)
			return -1;
		if (x.x > y.x)
			return 1;
		return 0;
	}
}

public class StatPoint {
	public double x, y;

	public StatPoint(double x, double y) {
		this.x = x;
		this.y = y;
	}
}

