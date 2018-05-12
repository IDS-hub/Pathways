using System;
using System.Collections;
using System.Collections.Generic;
using System.Linq;
using UnityEngine;

public class StatisticsWindowController : WindowController {
	[SerializeField] DrawLine2D drawLine;
	[SerializeField] RectTransform scrollContent;
	[SerializeField] GameObject cellPrefab;

	float Y_Multiplier = 59f;
	// one Y point. Reset in Start
	float X_Multiplier = 70f;
	// one X point
   
	List<StatData> data = new List<StatData>();
	List<GameObject> cells = new List<GameObject>();

	public void OnBackButtonClicked() {
		stateMachine.EnableLeftPanel(true);
	}

	public override void Start() {
		base.Start();

		if (Character.HideCharacter != null)
			Character.HideCharacter(true, false);

		Loader.Instane.ShowLoading();
		// getting the statistic graph data
		apiManager.GetUserStatistics((jsonData, success) => {
			Loader.Instane.RemoveLoading();
			if (success) {
				MiniJsonArray sesionArray = jsonData.GetJsonArray("res");
				if (sesionArray != null && sesionArray.Count > 0) {
					/*for (int i = 0; i < sesionArray.Count; i++) {
						data.Add(new StatData(sesionArray.Get(i).GetField("id", 0), sesionArray.Get(i).GetField("painLevel", 0) / sesionArray.Get(i).GetField("hitCount", 1), "", 
							0, sesionArray.Get(i).GetField("sessionDate", "")));
					}*/

					//{"res":[{"id":"14","painLevel":"12","sessionDate":"2018-03-30 14:05:58","hitCount":"3"}],"success":true}

					data.Add(new StatData(15, 1, "", 10, "2018-03-27 14:05:58"));
					data.Add(new StatData(15, 5, "", 10, "2018-03-22 14:05:58"));

					X_Multiplier = 800 / data.Count;
					DrawStatistics(data);
					DrawScrollBarCells(data);
				}
			} else {
				// user does not authenticated, logout
				Popup.Instance.ShowPopup(Constants.INVALID_SESSION_TITLE, Constants.INVALID_SESSION_DESC, () => {
					DoLogout();
				});
			}
		});
	}

	private void OnDisable() {
		drawLine.ResetStatistics();
		RemoveCells();
	}

	private void DrawScrollBarCells(List<StatData> data) {
		if (cells == null)
			cells = new List<GameObject>();

		scrollContent.sizeDelta = new Vector2(scrollContent.sizeDelta.x, data.Count * 100);
		foreach (var sessionData in data) {
			var goCell = Instantiate(cellPrefab, scrollContent.transform);
			cells.Add(goCell);
			goCell.GetComponent<StatisticCell>().InitCell(sessionData.Name, 
				sessionData.Duration.ToString() + " min",
				sessionData.PainLevel.ToString(),
				sessionData.SessionDate);
		}
	}

   

	void DrawStatistics(List <StatData> data) {
		data = data.OrderBy(o => Convert.ToDateTime(o.SessionDate)).ToList();
		List<LRPoint> linePoints = new List<LRPoint>();
		//var firstPoint = new LRPoint(Vector2.zero, UIUtilites.GetColorByPainIntensity(data[0].PainLevel), data[0].SessionDate); // first lineColor needs to be same as second point
		//linePoints.Add(firstPoint);
		for (int i = 0; i < data.Count; i++) {
			LRPoint p = new LRPoint(new Vector2((i) * X_Multiplier, data[i].PainLevel * Y_Multiplier), //tamal (i+1)
				            UIUtilites.GetColorByPainIntensity(data[i].PainLevel), 
				            data[i].SessionDate);
			linePoints.Add(p);
		}


		Debug.Log("points count " + linePoints.Count);
		drawLine.SetLinePoints(linePoints);
	}

    
	void RemoveCells() {
		for (int i = 0; i < cells.Count; i++) {
			Destroy(cells[i]);
		}
		cells.TrimExcess();
		cells = null;
	}
}

public class LRPoint {
	public Vector2 PointPosition;
	public Color PointColor;
	public string Date;

	public LRPoint(Vector2 pos, Color col, string date) {
		PointPosition = pos;
		PointColor = col;
		Date = date;
	}
}
